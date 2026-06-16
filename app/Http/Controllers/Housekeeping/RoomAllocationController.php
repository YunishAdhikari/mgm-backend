<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\RoomStatusUpdate;
use App\Models\User;
use App\Models\HousekeepingRoomAllocation;
use App\Models\RotaShift;
use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Log;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $allocatedRoomStatusIds = HousekeepingRoomAllocation::where('allocation_date', $date)
            ->pluck('room_status_update_id')
            ->toArray();

        $availableRooms = RoomStatusUpdate::with('room.roomType')
            ->where('status_date', $date)
            ->whereIn('status', [
                'departure',
                'stay',
                'carry_forward',
                'room_move',
            ])
            ->whereNotIn('id', $allocatedRoomStatusIds)
            ->orderBy('status')
            ->get();

        $availableRoomsByStatus = $availableRooms->groupBy('status');

        $staff = User::whereHas('rotaShifts', function ($q) use ($date) {
                $q->whereDate('shift_date', $date)
                    ->where('status', 'published')
                    ->whereNotIn('shift_type', [
                        'day_off',
                        'holiday',
                        'sick',
                    ]);
            })
            ->whereHas('department', function ($q) {
                $q->whereRaw('LOWER(name) IN (?, ?, ?)', [
                    'housekeeping',
                    'house keeping',
                    'hk',
                ]);
            })
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $allocations = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->where('allocation_date', $date)
            ->get()
            ->groupBy('assigned_to');

        return view('dashboard.housekeeping.allocation.index', compact(
            'date',
            'staff',
            'availableRooms',
            'availableRoomsByStatus',
            'allocations'
        ));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'allocation_date' => 'required|date',
            'room_status_update_ids' => 'required|array',
            'room_status_update_ids.*' => 'exists:room_status_updates,id',
        ]);

        $assignedUser = User::findOrFail($request->assigned_to);
        $roomNumbers = [];

        foreach ($request->room_status_update_ids as $roomStatusId) {
            $roomStatus = RoomStatusUpdate::with('room')->findOrFail($roomStatusId);

            HousekeepingRoomAllocation::updateOrCreate(
                [
                    'room_status_update_id' => $roomStatus->id,
                    'allocation_date' => $request->allocation_date,
                ],
                [
                    'room_id' => $roomStatus->room_id,
                    'assigned_to' => $request->assigned_to,
                    'assigned_by' => auth()->id(),
                    'cleaning_status' => 'assigned',
                ]
            );

            $roomNumbers[] = $roomStatus->room?->room_number ?? $roomStatus->room?->number ?? 'Room';
        }

        $this->sendPushToUser(
            $assignedUser,
            'HK Rooms Assigned',
            count($roomNumbers) . ' room(s) assigned for ' . $request->allocation_date,
            [
                'type' => 'housekeeping',
                'action' => 'rooms_assigned',
                'allocation_date' => (string) $request->allocation_date,
                'room_count' => (string) count($roomNumbers),
                'rooms' => implode(',', $roomNumbers),
            ]
        );

        return back()->with('success', 'Rooms allocated successfully.');
    }

    public function remove(HousekeepingRoomAllocation $allocation)
    {
        $allocation->load(['assignedTo', 'room']);

        $assignedUser = $allocation->assignedTo;
        $roomNumber = $allocation->room?->room_number ?? $allocation->room?->number ?? 'Room';

        $allocation->delete();

        if ($assignedUser) {
            $this->sendPushToUser(
                $assignedUser,
                'HK Room Removed',
                $roomNumber . ' has been removed from your allocation.',
                [
                    'type' => 'housekeeping',
                    'action' => 'room_removed',
                    'room' => (string) $roomNumber,
                ]
            );
        }

        return back()->with('success', 'Room allocation removed successfully.');
    }

    public function addExtraStaff(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,evening,night,split',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $user = User::with('department')->findOrFail($request->user_id);

        RotaShift::updateOrCreate(
            [
                'user_id' => $user->id,
                'shift_date' => $request->shift_date,
            ],
            [
                'department_id' => $user->department_id,
                'shift_type' => $request->shift_type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_minutes' => 0,
                'status' => 'published',
                'notes' => 'Added extra by HK Supervisor',
            ]
        );

        $this->sendPushToUser(
            $user,
            'HK Shift Added',
            'You have been added to the housekeeping rota on ' . $request->shift_date,
            [
                'type' => 'housekeeping',
                'action' => 'extra_shift_added',
                'shift_date' => (string) $request->shift_date,
                'shift_type' => (string) $request->shift_type,
            ]
        );

        return back()->with('success', 'Extra staff added successfully.');
    }

    public function markUnavailable(Request $request, RotaShift $rotaShift)
    {
        $request->validate([
            'shift_type' => 'required|in:sick,day_off,holiday',
        ]);

        $rotaShift->load('user');

        $rotaShift->update([
            'shift_type' => $request->shift_type,
            'notes' => 'Updated by HK Supervisor',
        ]);

        if ($rotaShift->user) {
            $this->sendPushToUser(
                $rotaShift->user,
                'HK Availability Updated',
                'Your rota has been updated to ' . str_replace('_', ' ', $request->shift_type),
                [
                    'type' => 'housekeeping',
                    'action' => 'availability_updated',
                    'shift_date' => (string) $rotaShift->shift_date,
                    'shift_type' => (string) $request->shift_type,
                ]
            );
        }

        return back()->with('success', 'Staff availability updated.');
    }

    public function autoAllocate(Request $request)
    {
        $request->validate([
            'allocation_date' => 'required|date',
        ]);

        $date = $request->allocation_date;

        $staff = User::whereHas('rotaShifts', function ($q) use ($date) {
            $q->whereDate('shift_date', $date)
                ->where('status', 'published')
                ->whereNotIn('shift_type', ['day_off', 'holiday', 'sick']);
        })
        ->whereHas('department', function ($q) {
            $q->whereRaw('LOWER(name) IN (?, ?, ?)', [
                'housekeeping',
                'house keeping',
                'hk',
            ]);
        })
        ->whereHas('role', function ($q) {
            $q->whereRaw('LOWER(name) NOT IN (?, ?, ?)', [
                'supervisor',
                'housekeeping supervisor',
                'hk supervisor',
            ]);
        })
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        if ($staff->count() === 0) {
            return back()->with('error', 'No housekeeping staff working on this date.');
        }

        $allocatedIds = HousekeepingRoomAllocation::where('allocation_date', $date)
            ->pluck('room_status_update_id')
            ->toArray();

        $rooms = RoomStatusUpdate::with('room')
            ->where('status_date', $date)
            ->whereIn('status', ['departure', 'stay', 'carry_forward', 'room_move'])
            ->whereNotIn('id', $allocatedIds)
            ->get();

        if ($rooms->count() === 0) {
            return back()->with('error', 'No available rooms to allocate.');
        }

        $staffWorkload = [];

        foreach ($staff as $employee) {
            $staffWorkload[$employee->id] = [
                'user' => $employee,
                'minutes' => 0,
                'rooms' => 0,
            ];
        }

        $rooms = $rooms->sortByDesc(function ($room) {
            return in_array($room->status, ['departure', 'carry_forward', 'room_move']) ? 30 : 15;
        });

        foreach ($rooms as $roomStatus) {
            $estimatedMinutes = in_array($roomStatus->status, ['departure', 'carry_forward', 'room_move'])
                ? 30
                : 15;

            uasort($staffWorkload, function ($a, $b) {
                return $a['minutes'] <=> $b['minutes'];
            });

            $selectedStaffId = array_key_first($staffWorkload);

            HousekeepingRoomAllocation::create([
                'room_status_update_id' => $roomStatus->id,
                'room_id' => $roomStatus->room_id,
                'assigned_to' => $selectedStaffId,
                'assigned_by' => auth()->id(),
                'allocation_date' => $date,
                'cleaning_status' => 'assigned',
                'estimated_minutes' => $estimatedMinutes,
            ]);

            $staffWorkload[$selectedStaffId]['minutes'] += $estimatedMinutes;
            $staffWorkload[$selectedStaffId]['rooms']++;
        }

        foreach ($staffWorkload as $staffId => $workload) {
            if ($workload['rooms'] <= 0) {
                continue;
            }

            $this->sendPushToUser(
                $workload['user'],
                'HK Rooms Auto Assigned',
                $workload['rooms'] . ' room(s) assigned for ' . $date,
                [
                    'type' => 'housekeeping',
                    'action' => 'auto_allocated',
                    'allocation_date' => (string) $date,
                    'room_count' => (string) $workload['rooms'],
                    'estimated_minutes' => (string) $workload['minutes'],
                ]
            );
        }

        logActivity('Generated Auto Allocation', 'Housekeeping', 'Rooms auto allocated for today');

        return back()->with('success', 'Rooms auto allocated successfully.');
    }

    private function sendPushToUser(?User $user, string $title, string $body, array $data = []): bool
    {
        if (!$user || empty($user->fcm_token)) {
            return false;
        }

        try {
            $firebase = new FirebaseNotificationService();

            $firebase->sendToToken(
                $user->fcm_token,
                $title,
                $body,
                $data
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('HK Firebase push failed for user ' . ($user->id ?? 'unknown') . ': ' . $e->getMessage());
            return false;
        }
    }
}
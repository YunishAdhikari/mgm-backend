<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\HousekeepingRoomAllocation;
use App\Models\MaintenanceJob;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HousekeepingStaffApiController extends Controller
{
    public function myRooms(Request $request)
    {
        $user = $request->user();

        $rooms = HousekeepingRoomAllocation::with(['room', 'roomStatusUpdate'])
            ->where('hotel_id', $user->hotel_id)
            ->where('assigned_to', $user->id)
            ->whereDate('allocation_date', today())
            ->get()
            ->sortBy(fn ($allocation) => (int) ($allocation->room->room_number ?? 0))
            ->values()
            ->map(function ($allocation) {
                $roomNumber = $allocation->room->room_number ?? '-';
                $cleaningStatus = $allocation->cleaning_status ?? 'assigned';

                return [
                    'id' => $allocation->id,
                    'room_id' => $allocation->room_id,
                    'room_number' => $roomNumber,
                    'floor' => is_numeric($roomNumber) ? substr($roomNumber, 0, 1) : '-',
                    'room_status' => $allocation->roomStatusUpdate->status ?? '',
                    'cleaning_status' => $cleaningStatus,
                    'count_as_cleaned' => in_array($cleaningStatus, ['inspection_pending', 'inspected']),
                    'display_status' => match ($cleaningStatus) {
                        'assigned' => 'Assigned',
                        'in_progress' => 'In Progress',
                        'inspection_pending' => 'Waiting Inspection',
                        'inspected' => 'Inspected',
                        'rejected' => 'Rejected - Redo Required',
                        'dnd' => 'Do Not Disturb',
                        'refused_service' => 'Refused Service',
                        'maintenance_required' => 'Maintenance Required',
                        default => ucfirst(str_replace('_', ' ', $cleaningStatus)),
                    },
                    'estimated_minutes' => $allocation->estimated_minutes,
                    'notes' => $allocation->notes,
                    'started_at' => $allocation->started_at,
                    'cleaned_at' => $allocation->cleaned_at,
                    'inspected_at' => $allocation->inspected_at,
                    'can_start' => in_array($cleaningStatus, ['assigned', 'rejected']),
                    'can_complete' => $cleaningStatus === 'in_progress',
                    'can_resubmit' => $cleaningStatus === 'rejected',
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Today allocated rooms fetched successfully.',
            'data' => $rooms,
        ]);
    }

    public function startCleaning(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        if (!in_array($allocation->cleaning_status, ['assigned', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => 'This room cannot be started now.',
            ], 422);
        }

        $allocation->update([
            'cleaning_status' => 'in_progress',
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cleaning started.',
        ]);
    }

    public function markCleaned(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        if (!in_array($allocation->cleaning_status, ['assigned', 'in_progress', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => 'This room cannot be marked as cleaned.',
            ], 422);
        }

        $allocation->load(['room', 'assignedTo']);

        $allocation->update([
            'cleaning_status' => 'inspection_pending',
            'cleaned_at' => now(),
        ]);

        $roomNumber = $allocation->room->room_number ?? '-';
        $hotelId = $request->user()->hotel_id;

        $this->sendPushToUsers(
            $this->hkSupervisors($hotelId),
            'Room Ready For Inspection',
            'Room ' . $roomNumber . ' has been cleaned by ' . ($request->user()->name ?? 'staff'),
            [
                'type' => 'housekeeping',
                'action' => 'room_cleaned',
                'allocation_id' => (string) $allocation->id,
                'room_number' => (string) $roomNumber,
                'hotel_id' => (string) $hotelId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Room marked as cleaned and sent for inspection.',
        ]);
    }

    public function markDnd(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        $allocation->load(['room', 'assignedTo']);

        $allocation->update([
            'cleaning_status' => 'dnd',
        ]);

        $roomNumber = $allocation->room->room_number ?? '-';
        $hotelId = $request->user()->hotel_id;

        $this->sendPushToUsers(
            $this->hkSupervisors($hotelId),
            'Room Marked DND',
            'Room ' . $roomNumber . ' was marked as DND by ' . ($request->user()->name ?? 'staff'),
            [
                'type' => 'housekeeping',
                'action' => 'room_dnd',
                'allocation_id' => (string) $allocation->id,
                'room_number' => (string) $roomNumber,
                'hotel_id' => (string) $hotelId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Room marked as DND.',
        ]);
    }

    public function markRefused(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        $allocation->load(['room', 'assignedTo']);

        $allocation->update([
            'cleaning_status' => 'refused_service',
        ]);

        $roomNumber = $allocation->room->room_number ?? '-';
        $hotelId = $request->user()->hotel_id;

        $this->sendPushToUsers(
            $this->hkSupervisors($hotelId),
            'Room Refused Service',
            'Room ' . $roomNumber . ' refused service. Updated by ' . ($request->user()->name ?? 'staff'),
            [
                'type' => 'housekeeping',
                'action' => 'room_refused',
                'allocation_id' => (string) $allocation->id,
                'room_number' => (string) $roomNumber,
                'hotel_id' => (string) $hotelId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Room marked as refused service.',
        ]);
    }

    public function markMaintenance(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        $request->validate([
            'issue' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $hotelId = $user->hotel_id;

        $allocation->load(['room', 'assignedTo']);

        $maintenanceDepartment = Department::where('hotel_id', $hotelId)
            ->whereRaw('LOWER(name) = ?', ['maintenance'])
            ->first();

        if (!$maintenanceDepartment) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance department not found for this hotel.',
            ], 404);
        }

        $roomNumber = $allocation->room->room_number ?? null;

        $job = MaintenanceJob::create([
            'hotel_id' => $hotelId,
            'reported_by' => $user->id,
            'department_id' => $maintenanceDepartment->id,
            'assigned_to' => null,
            'title' => 'Room maintenance required',
            'description' => $request->issue,
            'location' => 'Room',
            'room_number' => $roomNumber,
            'priority' => 'medium',
            'status' => 'pending',
            'reported_date' => today(),
        ]);

        $allocation->update([
            'cleaning_status' => 'maintenance_required',
            'notes' => $request->issue,
        ]);

        $this->sendPushToUsers(
            $this->maintenanceUsers($hotelId),
            'New Room Maintenance Issue',
            'Room ' . ($roomNumber ?? '-') . ': ' . $request->issue,
            [
                'type' => 'maintenance',
                'action' => 'created_from_housekeeping',
                'job_id' => (string) $job->id,
                'room_number' => (string) ($roomNumber ?? '-'),
                'hotel_id' => (string) $hotelId,
            ]
        );

        $this->sendPushToUsers(
            $this->hkSupervisors($hotelId),
            'HK Maintenance Reported',
            'Room ' . ($roomNumber ?? '-') . ' maintenance issue reported by ' . ($user->name ?? 'staff'),
            [
                'type' => 'housekeeping',
                'action' => 'maintenance_reported',
                'allocation_id' => (string) $allocation->id,
                'job_id' => (string) $job->id,
                'room_number' => (string) ($roomNumber ?? '-'),
                'hotel_id' => (string) $hotelId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Maintenance job created successfully.',
        ]);
    }

    public function supervisorProgress(Request $request)
    {
        $hotelId = $request->user()->hotel_id;

        $allocations = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->where('hotel_id', $hotelId)
            ->whereDate('allocation_date', today())
            ->get();

        $doneStatuses = ['inspection_pending', 'inspected'];

        $summary = [
            'total' => $allocations->count(),
            'pending' => $allocations->where('cleaning_status', 'assigned')->count(),
            'in_progress' => $allocations->where('cleaning_status', 'in_progress')->count(),
            'cleaned' => $allocations->whereIn('cleaning_status', $doneStatuses)->count(),
            'inspection_pending' => $allocations->where('cleaning_status', 'inspection_pending')->count(),
            'inspected' => $allocations->where('cleaning_status', 'inspected')->count(),
            'dnd' => $allocations->where('cleaning_status', 'dnd')->count(),
            'refused' => $allocations->where('cleaning_status', 'refused_service')->count(),
            'rejected' => $allocations->where('cleaning_status', 'rejected')->count(),
            'maintenance_required' => $allocations->where('cleaning_status', 'maintenance_required')->count(),
        ];

        $staff = $allocations
            ->groupBy('assigned_to')
            ->map(function ($items) use ($doneStatuses) {
                $first = $items->first();

                return [
                    'staff_id' => $first->assigned_to,
                    'staff_name' => $first->assignedTo->name ?? 'Unknown Staff',
                    'total' => $items->count(),
                    'pending' => $items->where('cleaning_status', 'assigned')->count(),
                    'in_progress' => $items->where('cleaning_status', 'in_progress')->count(),
                    'cleaned' => $items->whereIn('cleaning_status', $doneStatuses)->count(),
                    'inspection_pending' => $items->where('cleaning_status', 'inspection_pending')->count(),
                    'inspected' => $items->where('cleaning_status', 'inspected')->count(),
                    'dnd' => $items->where('cleaning_status', 'dnd')->count(),
                    'refused' => $items->where('cleaning_status', 'refused_service')->count(),
                    'rejected' => $items->where('cleaning_status', 'rejected')->count(),
                    'maintenance_required' => $items->where('cleaning_status', 'maintenance_required')->count(),
                    'rooms' => $items->map(function ($allocation) {
                        return [
                            'id' => $allocation->id,
                            'room_number' => $allocation->room->room_number ?? '-',
                            'room_status' => $allocation->roomStatusUpdate->status ?? '',
                            'cleaning_status' => $allocation->cleaning_status ?? 'assigned',
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'HK supervisor progress fetched successfully.',
            'data' => [
                'summary' => $summary,
                'staff' => $staff,
            ],
        ]);
    }

    public function inspectionQueue(Request $request)
    {
        $hotelId = $request->user()->hotel_id;

        $rooms = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->where('hotel_id', $hotelId)
            ->whereDate('allocation_date', today())
            ->where('cleaning_status', 'inspection_pending')
            ->orderBy('cleaned_at', 'asc')
            ->get()
            ->map(function ($allocation) {
                return [
                    'id' => $allocation->id,
                    'room_number' => $allocation->room->room_number ?? '-',
                    'room_status' => $allocation->roomStatusUpdate->status ?? '',
                    'cleaning_status' => $allocation->cleaning_status,
                    'staff_name' => $allocation->assignedTo->name ?? 'Unknown Staff',
                    'cleaned_at' => $allocation->cleaned_at,
                    'notes' => $allocation->notes,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Inspection queue fetched successfully.',
            'data' => $rooms,
        ]);
    }

    public function approveInspection(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkHotelAccess($request, $allocation);

        if ($allocation->cleaning_status !== 'inspection_pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only rooms waiting for inspection can be approved.',
            ], 422);
        }

        $allocation->load(['room', 'assignedTo']);

        $allocation->update([
            'cleaning_status' => 'inspected',
            'inspected_at' => now(),
            'inspected_by' => auth()->id(),

        ]);

        $roomNumber = $allocation->room->room_number ?? '-';

        $this->sendPushToUser(
            $allocation->assignedTo,
            'Room Approved',
            'Room ' . $roomNumber . ' has passed inspection.',
            [
                'type' => 'housekeeping',
                'action' => 'inspection_approved',
                'allocation_id' => (string) $allocation->id,
                'room_number' => (string) $roomNumber,
                'hotel_id' => (string) $allocation->hotel_id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Room approved successfully.',
        ]);
    }

    public function rejectInspection(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkHotelAccess($request, $allocation);

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if ($allocation->cleaning_status !== 'inspection_pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only rooms waiting for inspection can be rejected.',
            ], 422);
        }

        $allocation->load(['room', 'assignedTo']);

        $allocation->update([
            'cleaning_status' => 'rejected',
            'notes' => $request->reason,
            'cleaned_at' => null,
            'inspected_at' => null,
        ]);

        $roomNumber = $allocation->room->room_number ?? '-';

        $this->sendPushToUser(
            $allocation->assignedTo,
            'Room Inspection Rejected',
            'Room ' . $roomNumber . ' needs attention. Reason: ' . $request->reason,
            [
                'type' => 'housekeeping',
                'action' => 'inspection_rejected',
                'allocation_id' => (string) $allocation->id,
                'room_number' => (string) $roomNumber,
                'hotel_id' => (string) $allocation->hotel_id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Room rejected and sent back to staff.',
        ]);
    }

private function checkOwnership(Request $request, HousekeepingRoomAllocation $allocation): void
{
    $user = $request->user();

    if ((int) $allocation->hotel_id !== (int) $user->hotel_id) {
        abort(403, 'You are not allowed to access this hotel room.');
    }

    if ((int) $allocation->assigned_to !== (int) $user->id) {
        abort(403, 'You are not allowed to update this room.');
    }

    if (Carbon::parse($allocation->allocation_date)->toDateString() !== today()->toDateString()) {
        abort(403, 'You can only update today allocated rooms.');
    }
}

private function checkHotelAccess(Request $request, HousekeepingRoomAllocation $allocation): void
{
    if ((int) $allocation->hotel_id !== (int) $request->user()->hotel_id) {
        abort(403, 'You are not allowed to access this hotel room.');
    }

    if (Carbon::parse($allocation->allocation_date)->toDateString() !== today()->toDateString()) {
        abort(403, 'You can only update today allocated rooms.');
    }
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
            Log::error('HK API push failed for user ' . ($user->id ?? 'unknown') . ': ' . $e->getMessage());
            return false;
        }
    }

    private function sendPushToUsers($users, string $title, string $body, array $data = []): int
    {
        $sent = 0;

        foreach ($users as $user) {
            if ($this->sendPushToUser($user, $title, $body, $data)) {
                $sent++;
            }
        }

        return $sent;
    }

    private function hkSupervisors(int $hotelId)
    {
        return User::where('hotel_id', $hotelId)
            ->whereHas('department', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId)
                    ->whereRaw('LOWER(name) IN (?, ?, ?)', [
                        'housekeeping',
                        'house keeping',
                        'hk',
                    ]);
            })
            ->whereHas('role', function ($q) {
                $q->whereRaw('LOWER(name) IN (?, ?, ?)', [
                    'supervisor',
                    'housekeeping supervisor',
                    'hk supervisor',
                ]);
            })
            ->whereNotNull('fcm_token')
            ->get();
    }

    private function maintenanceUsers(int $hotelId)
    {
        return User::where('hotel_id', $hotelId)
            ->whereHas('department', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId)
                    ->whereRaw('LOWER(name) = ?', ['maintenance']);
            })
            ->whereNotNull('fcm_token')
            ->get();
    }
}
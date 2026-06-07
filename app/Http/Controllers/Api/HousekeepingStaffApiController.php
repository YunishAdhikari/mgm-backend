<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\HousekeepingRoomAllocation;
use App\Models\MaintenanceJob;
use Illuminate\Http\Request;

class HousekeepingStaffApiController extends Controller
{
  public function myRooms(Request $request)
{
    $user = $request->user();

    $rooms = HousekeepingRoomAllocation::with([
            'room',
            'roomStatusUpdate',
        ])
        ->where('assigned_to', $user->id)
        ->whereDate('allocation_date', today())
        ->get()
        ->sortBy(function ($allocation) {
            return (int) ($allocation->room->room_number ?? 0);
        })
        ->values()
        ->map(function ($allocation) {
            $roomNumber = $allocation->room->room_number ?? '-';

            return [
                'id' => $allocation->id,
                'room_id' => $allocation->room_id,
                'room_number' => $roomNumber,
                'floor' => is_numeric($roomNumber)
                    ? substr($roomNumber, 0, 1)
                    : '-',
                'room_status' => $allocation->roomStatusUpdate->status ?? '',
                'cleaning_status' => $allocation->cleaning_status ?? 'assigned',
                'estimated_minutes' => $allocation->estimated_minutes,
                'notes' => $allocation->notes,
                'started_at' => $allocation->started_at,
                'cleaned_at' => $allocation->cleaned_at,
                'inspected_at' => $allocation->inspected_at,
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

        $allocation->update([
            'cleaning_status' => 'cleaned',
            'cleaned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room marked as cleaned.',
        ]);
    }

    public function markDnd(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        $allocation->update([
            'cleaning_status' => 'dnd',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room marked as DND.',
        ]);
    }

    public function markRefused(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->checkOwnership($request, $allocation);

        $allocation->update([
            'cleaning_status' => 'refused_service',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room marked as refused service.',
        ]);
    }

    private function checkOwnership(Request $request, HousekeepingRoomAllocation $allocation)
    {
        if ((int) $allocation->assigned_to !== (int) $request->user()->id) {
            abort(403, 'You are not allowed to update this room.');
        }

        if ($allocation->allocation_date !== today()->toDateString()) {
            abort(403, 'You can only update today allocated rooms.');
        }
    }


    public function markMaintenance(Request $request, HousekeepingRoomAllocation $allocation)
{
    $this->checkOwnership($request, $allocation);

    $request->validate([
        'issue' => 'required|string|max:1000',
    ]);

    $maintenanceDepartment = Department::where('name', 'Maintenance')->first();

    if (!$maintenanceDepartment) {
        return response()->json([
            'success' => false,
            'message' => 'Maintenance department not found.',
        ], 404);
    }

    $roomNumber = $allocation->room->room_number ?? null;

    MaintenanceJob::create([
        'reported_by' => $request->user()->id,
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

    return response()->json([
        'success' => true,
        'message' => 'Maintenance job created successfully.',
    ]);
}

public function supervisorProgress(Request $request)
{
    $allocations = HousekeepingRoomAllocation::with([
            'room',
            'assignedTo',
            'roomStatusUpdate',
        ])
        ->whereDate('allocation_date', today())
        ->get();

    $summary = [
        'total' => $allocations->count(),
        'pending' => $allocations->whereIn('cleaning_status', ['assigned', 'pending'])->count(),
        'in_progress' => $allocations->where('cleaning_status', 'in_progress')->count(),
        'cleaned' => $allocations->where('cleaning_status', 'cleaned')->count(),
        'dnd' => $allocations->where('cleaning_status', 'dnd')->count(),
        'refused' => $allocations->where('cleaning_status', 'refused_service')->count(),
    ];

    $staff = $allocations
        ->groupBy('assigned_to')
        ->map(function ($items) {
            $first = $items->first();

            return [
                'staff_id' => $first->assigned_to,
                'staff_name' => $first->assignedTo->name ?? 'Unknown Staff',
                'total' => $items->count(),
                'pending' => $items->whereIn('cleaning_status', ['assigned', 'pending'])->count(),
                'in_progress' => $items->where('cleaning_status', 'in_progress')->count(),
                'cleaned' => $items->where('cleaning_status', 'cleaned')->count(),
                'dnd' => $items->where('cleaning_status', 'dnd')->count(),
                'refused' => $items->where('cleaning_status', 'refused_service')->count(),
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
    $rooms = HousekeepingRoomAllocation::with([
            'room',
            'assignedTo',
            'roomStatusUpdate',
        ])
        ->whereDate('allocation_date', today())
        ->where('cleaning_status', 'cleaned')
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
    $allocation->update([
        'cleaning_status' => 'inspected',
        'inspected_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Room approved successfully.',
    ]);
}

public function rejectInspection(Request $request, HousekeepingRoomAllocation $allocation)
{
    $request->validate([
        'reason' => 'required|string|max:1000',
    ]);

    $allocation->update([
        'cleaning_status' => 'assigned',
        'notes' => $request->reason,
        'cleaned_at' => null,
        'inspected_at' => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Room rejected and sent back to staff.',
    ]);
}
}
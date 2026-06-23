<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingRoomAllocation;
use App\Models\RoomStatusUpdate;
use Illuminate\Http\Request;

class HousekeepingInspectionController extends Controller
{
    private function hotelId(): int
    {
        return (int) auth()->user()->hotel_id;
    }

    public function index()
    {
        $hotelId = $this->hotelId();

        $rooms = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->where('hotel_id', $hotelId)
            ->whereDate('allocation_date', today())
            ->where('cleaning_status', 'cleaned')
            ->orderBy('cleaned_at', 'asc')
            ->get();

        return view('dashboard.housekeeping.inspection.index', compact('rooms'));
    }

    public function approve(HousekeepingRoomAllocation $allocation)
    {
        $this->ensureAllocationBelongsToHotel($allocation);

        $allocation->update([
            'cleaning_status' => 'inspected',
            'inspected_at' => now(),
        ]);

        return back()->with('success', 'Room approved successfully.');
    }

    public function reject(Request $request, HousekeepingRoomAllocation $allocation)
    {
        $this->ensureAllocationBelongsToHotel($allocation);

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $allocation->update([
            'cleaning_status' => 'assigned',
            'notes' => $request->reason,
            'cleaned_at' => null,
            'inspected_at' => null,
        ]);

        return back()->with('success', 'Room rejected and sent back to staff.');
    }

    public function staydnd()
    {
        $hotelId = $this->hotelId();

        $rooms = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->where('hotel_id', $hotelId)
            ->whereDate('allocation_date', today())
            ->whereIn('cleaning_status', [
                'assigned',
                'pending',
                'in_progress',
                'dnd',
                'refused_service',
            ])
            ->orderBy('cleaning_status')
            ->get()
            ->sortBy(function ($allocation) {
                return (int) ($allocation->room->room_number ?? 0);
            })
            ->values();

        $stats = [
            'total' => $rooms->count(),
            'assigned' => $rooms->whereIn('cleaning_status', ['assigned', 'pending'])->count(),
            'in_progress' => $rooms->where('cleaning_status', 'in_progress')->count(),
            'dnd' => $rooms->where('cleaning_status', 'dnd')->count(),
            'refused' => $rooms->where('cleaning_status', 'refused_service')->count(),
        ];

        return view('dashboard.housekeeping.inspection.dnd', compact('rooms', 'stats'));
    }

    public function ooo()
    {
        $hotelId = $this->hotelId();

        $rooms = RoomStatusUpdate::with('room')
            ->where('hotel_id', $hotelId)
            ->whereDate('status_date', today())
            ->whereIn('status', ['OOO', 'OOI'])
            ->get()
            ->sortBy(function ($item) {
                return (int) ($item->room->room_number ?? 0);
            })
            ->values();

        $stats = [
            'total' => $rooms->count(),
            'ooo' => $rooms->where('status', 'OOO')->count(),
            'ooi' => $rooms->where('status', 'OOI')->count(),
        ];

        return view('dashboard.housekeeping.inspection.ooo', compact('rooms', 'stats'));
    }
public function inspectedRooms()
{
    $hotelId = $this->hotelId();

    $rooms = HousekeepingRoomAllocation::with([
            'room',
            'assignedTo',
            'inspectedBy',
            'roomStatusUpdate',
        ])
        ->where('hotel_id', $hotelId)
        ->where('cleaning_status', 'inspected')
        ->whereDate('allocation_date', today())
        ->latest('inspected_at')
        ->get();

    return view('dashboard.housekeeping.inspected-rooms', compact('rooms'));
}

    private function ensureAllocationBelongsToHotel(HousekeepingRoomAllocation $allocation): void
    {
        if ((int) $allocation->hotel_id !== $this->hotelId()) {
            abort(403);
        }
    }
}
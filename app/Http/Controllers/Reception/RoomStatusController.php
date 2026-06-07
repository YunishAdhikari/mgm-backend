<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomStatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomStatusController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $rooms = Room::with([
                'roomType',
                'statusUpdates' => function ($query) use ($date) {
                    $query->where('status_date', $date);
                }
            ])
            ->where('is_active', true)
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get()
            ->groupBy('floor');

        return view('dashboard.reception.room-status.index', compact('rooms', 'date'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'status_date' => 'required|date',
            'status' => 'required|in:departure,stay,room_move,carry_forward,OOO,OOI',
            'notes' => 'nullable|string',
        ]);

        RoomStatusUpdate::updateOrCreate(
            [
                'room_id' => $request->room_id,
                'status_date' => $request->status_date,
            ],
            [
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Room status updated successfully.');
    }
}
<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\RoomStatusUpdate;
use Illuminate\Http\Request;

class HousekeepingBoardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $hotelId = auth()->user()->hotel_id;

        $statuses = RoomStatusUpdate::with(['room.roomType', 'updatedBy'])
            ->where('hotel_id', $hotelId)
            ->whereDate('status_date', $date)
            ->whereIn('status', [
                'departure',
                'stay',
                'room_move',
                'carry_forward',
            ])
            ->get()
            ->groupBy('status');

        return view('dashboard.housekeeping.board.index', compact(
            'statuses',
            'date'
        ));
    }
}
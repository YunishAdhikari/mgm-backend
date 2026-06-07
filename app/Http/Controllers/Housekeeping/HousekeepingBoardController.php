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

        $statuses = RoomStatusUpdate::with(['room.roomType', 'updatedBy'])
            ->where('status_date', $date)
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
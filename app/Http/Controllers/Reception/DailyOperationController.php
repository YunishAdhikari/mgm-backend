<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\DailyOperation;
use App\Models\Room;
use Illuminate\Http\Request;

class DailyOperationController extends Controller
{
    public function index(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;
        $date = $request->date ?? today()->toDateString();

        $totalRooms = Room::where('hotel_id', $hotelId)
            ->where('is_active', true)
            ->count();

        $am = DailyOperation::where('hotel_id', $hotelId)
            ->whereDate('operation_date', $date)
            ->where('snapshot', 'AM')
            ->first();

        $pm = DailyOperation::where('hotel_id', $hotelId)
            ->whereDate('operation_date', $date)
            ->where('snapshot', 'PM')
            ->first();

        return view('dashboard.reception.daily-operations.index', compact(
            'date',
            'totalRooms',
            'am',
            'pm'
        ));
    }

    public function store(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $data = $request->validate([
            'operation_date' => 'required|date',
            'snapshot' => 'required|in:AM,PM',

            'arrivals' => 'required|integer|min:0',
            'departures' => 'required|integer|min:0',
            'stayovers' => 'required|integer|min:0',
            'ooo_rooms' => 'required|integer|min:0',
            'ooi_rooms' => 'required|integer|min:0',

            'vip_arrivals' => 'nullable|integer|min:0',
            'group_arrivals' => 'nullable|integer|min:0',
            'group_departures' => 'nullable|integer|min:0',
            'expected_breakfast' => 'nullable|integer|min:0',
            'expected_dinner' => 'nullable|integer|min:0',

            'notes' => 'nullable|string|max:2000',
            'is_finalised' => 'nullable|boolean',
        ]);

        $existing = DailyOperation::where('hotel_id', $hotelId)
            ->whereDate('operation_date', $data['operation_date'])
            ->where('snapshot', $data['snapshot'])
            ->first();

        if ($existing && $existing->is_finalised) {
            return back()->with('error', $data['snapshot'] . ' forecast is already finalised and cannot be changed.');
        }

        DailyOperation::updateOrCreate(
            [
                'hotel_id' => $hotelId,
                'operation_date' => $data['operation_date'],
                'snapshot' => $data['snapshot'],
            ],
            [
                'arrivals' => $data['arrivals'],
                'departures' => $data['departures'],
                'stayovers' => $data['stayovers'],
                'ooo_rooms' => $data['ooo_rooms'],
                'ooi_rooms' => $data['ooi_rooms'],
                'vip_arrivals' => $data['vip_arrivals'] ?? 0,
                'group_arrivals' => $data['group_arrivals'] ?? 0,
                'group_departures' => $data['group_departures'] ?? 0,
                'expected_breakfast' => $data['expected_breakfast'] ?? 0,
                'expected_dinner' => $data['expected_dinner'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'is_finalised' => $request->boolean('is_finalised'),
                'created_by' => auth()->id(),
            ]
        );

        return back()->with('success', $data['snapshot'] . ' forecast saved successfully.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{

public function index(){
    $rooms = Room::with('roomType')->orderBy('room_number')->get();

$roomTypes = RoomType::where('is_active', true)
    ->orderBy('name')
    ->get();

return view('dashboard.admin.rooms.index', compact('rooms', 'roomTypes'));
}

   

public function store(Request $request)
{
    $request->validate([
        'room_number' => 'required|string|max:50|unique:rooms,room_number',
        'room_type_id' => 'nullable|exists:room_types,id',
        'floor' => 'nullable|string|max:50',
        'max_occupancy' => 'required|integer|min:1',
        'is_active' => 'nullable|boolean',
        'notes' => 'nullable|string',
    ]);

    Room::create([
        'room_number' => $request->room_number,
        'room_type_id' => $request->room_type_id,
        'floor' => $request->floor,
        'max_occupancy' => $request->max_occupancy,
        'is_active' => $request->has('is_active'),
        'notes' => $request->notes,
    ]);

    return redirect()->route('rooms.index')
        ->with('success', 'Room added successfully.');
}

public function edit(Room $room)
{
    $roomTypes = RoomType::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('dashboard.admin.rooms.edit', compact('room', 'roomTypes'));
}

public function update(Request $request, Room $room)
{
    $request->validate([
        'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
        'room_type_id' => 'nullable|exists:room_types,id',
        'floor' => 'nullable|string|max:50',
        'max_occupancy' => 'required|integer|min:1',
        'is_active' => 'nullable|boolean',
        'notes' => 'nullable|string',
    ]);

    $room->update([
        'room_number' => $request->room_number,
        'room_type_id' => $request->room_type_id,
        'floor' => $request->floor,
        'max_occupancy' => $request->max_occupancy,
        'is_active' => $request->has('is_active'),
        'notes' => $request->notes,
    ]);

    return redirect()->route('rooms.index')
        ->with('success', 'Room updated successfully.');
}
}
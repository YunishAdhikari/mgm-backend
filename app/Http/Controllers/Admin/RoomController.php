<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Hotel $hotel)
    {
        $rooms = Room::with('roomType')
            ->where('hotel_id', $hotel->id)
            ->orderByRaw('CAST(room_number AS UNSIGNED), room_number')
            ->get();

        $roomTypes = RoomType::where('hotel_id', $hotel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.rooms.index', compact('hotel', 'rooms', 'roomTypes'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,NULL,id,hotel_id,' . $hotel->id,
            'room_type_id' => 'nullable|exists:room_types,id',
            'floor' => 'nullable|string|max:50',
            'max_occupancy' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,dirty,inspected,out_of_order,out_of_service',
            'housekeeping_status' => 'required|in:clean,dirty,in_progress,inspection_pending,inspected,rejected,dnd,refused_service',
            'maintenance_status' => 'required|in:clear,maintenance_required,out_of_order,out_of_service',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if (!empty($data['room_type_id'])) {
            RoomType::where('hotel_id', $hotel->id)
                ->where('id', $data['room_type_id'])
                ->firstOrFail();
        }

        Room::create([
            'hotel_id' => $hotel->id,
            'room_number' => $data['room_number'],
            'room_type_id' => $data['room_type_id'] ?? null,
            'floor' => $data['floor'] ?? null,
            'max_occupancy' => $data['max_occupancy'],
            'status' => $data['status'],
            'housekeeping_status' => $data['housekeeping_status'],
            'maintenance_status' => $data['maintenance_status'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Room added successfully.');
    }

    public function edit(Hotel $hotel, Room $room)
    {
        $this->ensureRoomBelongsToHotel($hotel, $room);

        $roomTypes = RoomType::where('hotel_id', $hotel->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.rooms.edit', compact('hotel', 'room', 'roomTypes'));
    }

    public function update(Request $request, Hotel $hotel, Room $room)
    {
        $this->ensureRoomBelongsToHotel($hotel, $room);

        $data = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id . ',id,hotel_id,' . $hotel->id,
            'room_type_id' => 'nullable|exists:room_types,id',
            'floor' => 'nullable|string|max:50',
            'max_occupancy' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,dirty,inspected,out_of_order,out_of_service',
            'housekeeping_status' => 'required|in:clean,dirty,in_progress,inspection_pending,inspected,rejected,dnd,refused_service',
            'maintenance_status' => 'required|in:clear,maintenance_required,out_of_order,out_of_service',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if (!empty($data['room_type_id'])) {
            RoomType::where('hotel_id', $hotel->id)
                ->where('id', $data['room_type_id'])
                ->firstOrFail();
        }

        $room->update([
            'room_number' => $data['room_number'],
            'room_type_id' => $data['room_type_id'] ?? null,
            'floor' => $data['floor'] ?? null,
            'max_occupancy' => $data['max_occupancy'],
            'status' => $data['status'],
            'housekeeping_status' => $data['housekeeping_status'],
            'maintenance_status' => $data['maintenance_status'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.hotels.rooms.index', $hotel)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Hotel $hotel, Room $room)
    {
        $this->ensureRoomBelongsToHotel($hotel, $room);

        $room->delete();

        return back()->with('success', 'Room deleted successfully.');
    }

    private function ensureRoomBelongsToHotel(Hotel $hotel, Room $room): void
    {
        if ((int) $room->hotel_id !== (int) $hotel->id) {
            abort(404);
        }
    }
}
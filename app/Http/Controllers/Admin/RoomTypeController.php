<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Hotel $hotel)
    {
        $roomTypes = RoomType::where('hotel_id', $hotel->id)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.room-type.index', compact('hotel', 'roomTypes'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name,NULL,id,hotel_id,' . $hotel->id,
            'code' => 'nullable|string|max:20|unique:room_types,code,NULL,id,hotel_id,' . $hotel->id,
            'default_pax' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string|max:1000',
            'colour' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'default_pax' => $data['default_pax'],
            'description' => $data['description'] ?? null,
            'colour' => $data['colour'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Room type added successfully.');
    }

    public function update(Request $request, Hotel $hotel, RoomType $roomType)
    {
        $this->ensureRoomTypeBelongsToHotel($hotel, $roomType);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name,' . $roomType->id . ',id,hotel_id,' . $hotel->id,
            'code' => 'nullable|string|max:20|unique:room_types,code,' . $roomType->id . ',id,hotel_id,' . $hotel->id,
            'default_pax' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string|max:1000',
            'colour' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $roomType->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'default_pax' => $data['default_pax'],
            'description' => $data['description'] ?? null,
            'colour' => $data['colour'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Room type updated successfully.');
    }

    public function destroy(Hotel $hotel, RoomType $roomType)
    {
        $this->ensureRoomTypeBelongsToHotel($hotel, $roomType);

        $roomType->delete();

        return back()->with('success', 'Room type deleted successfully.');
    }

    private function ensureRoomTypeBelongsToHotel(Hotel $hotel, RoomType $roomType): void
    {
        if ((int) $roomType->hotel_id !== (int) $hotel->id) {
            abort(404);
        }
    }
}
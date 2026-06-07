<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::orderBy('name')->get();

        return view('dashboard.admin.room-type.index', compact('roomTypes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name',
            // 'is_active' => 'nullable|boolean',
        ]);


        RoomType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Room type added successfully.');
    }

    public function edit(RoomType $roomType)
    {
        return view('dashboard.admin.room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:room_types,name,' . $roomType->id,
            'is_active' => 'nullable|boolean',
        ]);

        $roomType->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType)
{
    $roomType->delete();

    return back()->with('success', 'Room Type deleted successfully.');
}
}
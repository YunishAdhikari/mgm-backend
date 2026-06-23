<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Hotel $hotel)
    {
        $restaurants = Restaurant::where('hotel_id', $hotel->id)
            ->withCount('tables')
            ->latest()
            ->get();

        return view('dashboard.admin.restaurants.index', compact('hotel', 'restaurants'));
    }

    public function store(Request $request, Hotel $hotel)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:restaurants,name,NULL,id,hotel_id,' . $hotel->id,
        'description' => 'nullable|string|max:1000',
        'is_active' => 'nullable|boolean',
    ]);

    Restaurant::create([
        'hotel_id' => $hotel->id,
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'is_active' => $request->boolean('is_active'),
    ]);

    return back()->with('success', 'Restaurant created successfully.');
}
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:restaurants,name,' . $restaurant->id . ',id,hotel_id,' . $restaurant->hotel_id,
            'description' => 'nullable|string|max:1000',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'slot_duration_minutes' => 'required|integer|min:5|max:240',
            'slot_interval_minutes' => 'required|integer|min:5|max:240',
            'max_pax_per_slot' => 'required|integer|min:1|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $restaurant->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'opening_time' => $validated['opening_time'] ?? null,
            'closing_time' => $validated['closing_time'] ?? null,
            'slot_duration_minutes' => $validated['slot_duration_minutes'],
            'slot_interval_minutes' => $validated['slot_interval_minutes'],
            'max_pax_per_slot' => $validated['max_pax_per_slot'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        if ($restaurant->tables()->count() > 0) {
            return back()->withErrors([
                'delete' => 'This restaurant cannot be deleted because it has tables assigned.',
            ]);
        }

        $restaurant->delete();

        return back()->with('success', 'Restaurant deleted successfully.');
    }
}
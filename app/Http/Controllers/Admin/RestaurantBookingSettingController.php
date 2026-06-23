<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\RestaurantBookingSetting;
use Illuminate\Http\Request;

class RestaurantBookingSettingController extends Controller
{
    public function index(Hotel $hotel, Restaurant $restaurant)
    {
        $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

        $settings = RestaurantBookingSetting::where('restaurant_id', $restaurant->id)
            ->get()
            ->keyBy('booking_type');

        return view('dashboard.admin.restaurants.settings.index', compact(
            'hotel',
            'restaurant',
            'settings'
        ));
    }

    public function store(Request $request, Hotel $hotel, Restaurant $restaurant)
    {
        $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

        $data = $request->validate([
            'booking_type' => 'required|in:afternoon_tea,dinner',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'slot_duration_minutes' => 'required|integer|min:5|max:240',
            'slot_interval_minutes' => 'required|integer|min:5|max:240',
            'max_pax_per_slot' => 'required|integer|min:1|max:1000',
            'allow_overbooking' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        RestaurantBookingSetting::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'booking_type' => $data['booking_type'],
            ],
            [
                'opening_time' => $data['opening_time'],
                'closing_time' => $data['closing_time'],
                'slot_duration_minutes' => $data['slot_duration_minutes'],
                'slot_interval_minutes' => $data['slot_interval_minutes'],
                'max_pax_per_slot' => $data['max_pax_per_slot'],
                'allow_overbooking' => $request->boolean('allow_overbooking'),
                'is_active' => $request->boolean('is_active'),
            ]
        );

        return back()->with('success', 'Restaurant booking setting saved successfully.');
    }

    private function ensureRestaurantBelongsToHotel(Hotel $hotel, Restaurant $restaurant): void
    {
        if ((int) $restaurant->hotel_id !== (int) $hotel->id) {
            abort(404);
        }
    }
}
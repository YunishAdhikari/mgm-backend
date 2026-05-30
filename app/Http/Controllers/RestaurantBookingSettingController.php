<?php

namespace App\Http\Controllers;

use App\Models\RestaurantBookingSetting;
use Illuminate\Http\Request;

class RestaurantBookingSettingController extends Controller
{
    public function index()
    {
        $settings = RestaurantBookingSetting::all()->keyBy('booking_type');

        return view('dashboard.admin.restaurant.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_type' => 'required|in:afternoon_tea,dinner',
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
            'slot_duration_minutes' => 'required|integer|min:5',
            'max_pax_per_slot' => 'required|integer|min:1',
            'allow_overbooking' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        RestaurantBookingSetting::updateOrCreate(
            ['booking_type' => $data['booking_type']],
            [
                'opening_time' => $data['opening_time'],
                'closing_time' => $data['closing_time'],
                'slot_duration_minutes' => $data['slot_duration_minutes'],
                'max_pax_per_slot' => $data['max_pax_per_slot'],
                'allow_overbooking' => $request->boolean('allow_overbooking'),
                'is_active' => $request->boolean('is_active'),
            ]
        );

        return back()->with('success', 'Restaurant setting saved successfully.');
    }


    
}
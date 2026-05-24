<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class AdminAttendanceSettingController extends Controller
{
    public function edit()
    {
        $setting = AttendanceSetting::firstOrCreate([]);

        return view('dashboard.admin.attendance-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hotel_wifi_ip' => 'nullable|string|max:255',
            'hotel_latitude' => 'nullable|numeric',
            'hotel_longitude' => 'nullable|numeric',
            'allowed_radius_meters' => 'required|integer|min:10',
            'is_ip_check_enabled' => 'nullable',
            'is_location_check_enabled' => 'nullable',
        ]);

        $setting = AttendanceSetting::firstOrCreate([]);

        $setting->update([
            'hotel_wifi_ip' => $request->hotel_wifi_ip,
            'hotel_latitude' => $request->hotel_latitude,
            'hotel_longitude' => $request->hotel_longitude,
            'allowed_radius_meters' => $request->allowed_radius_meters,
            'is_ip_check_enabled' => $request->has('is_ip_check_enabled'),
            'is_location_check_enabled' => $request->has('is_location_check_enabled'),
        ]);

        return back()->with('success', 'Attendance settings updated successfully.');
    }
}
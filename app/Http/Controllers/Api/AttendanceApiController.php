<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceApiController extends Controller
{


private function isWithinRadius(
    float $userLat,
    float $userLng,
    float $hotelLat,
    float $hotelLng,
    int $allowedRadius
): bool {

    $earthRadius = 6371000;

    $latFrom = deg2rad($hotelLat);
    $lonFrom = deg2rad($hotelLng);

    $latTo = deg2rad($userLat);
    $lonTo = deg2rad($userLng);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(
        pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) *
        pow(sin($lonDelta / 2), 2)
    ));

    $distance = $angle * $earthRadius;

    return $distance <= $allowedRadius;
}
    // private array $allowedIps = [
    //     // Add your hotel Wi-Fi public IP here
    //     // Example: '81.123.45.67',
    //     // '127.0.0.1',
    //     ' 192.168.0.10',
            
    // ];

    // private function isAllowedIp(Request $request): bool
    // {
    //     return in_array($request->ip(), $this->allowedIps);
    // }

//     private function isAllowedIp(Request $request): bool
// {
//     return true;
// }

private function isAllowedIp(Request $request): bool
{
    $settings = \App\Models\AttendanceSetting::first();

    if (!$settings || !$settings->is_ip_check_enabled) {
        return true;
    }

    $requestIp = $request->ip();

    $allowedIps = [
        $settings->hotel_wifi_ip,
        '127.0.0.1',
        '::1',
    ];

    // Allow private local network for testing
    if (
        str_starts_with($requestIp, '192.168.') ||
        str_starts_with($requestIp, '172.') ||
        str_starts_with($requestIp, '10.')
    ) {
        return true;
    }

    return in_array($requestIp, array_filter($allowedIps));
}


private function isAllowedLocation(Request $request): bool
{
    $settings = \App\Models\AttendanceSetting::first();

    if (!$settings || !$settings->is_location_check_enabled) {
        return true;
    }

    if (
        !$request->latitude ||
        !$request->longitude ||
        !$settings->hotel_latitude ||
        !$settings->hotel_longitude
    ) {
        return false;
    }

    Log::info('GPS CHECK', [
        'phone_lat' => $request->latitude,
        'phone_lng' => $request->longitude,
        'hotel_lat' => $settings->hotel_latitude,
        'hotel_lng' => $settings->hotel_longitude,
        'radius' => $settings->allowed_radius_meters,
    ]);

    return $this->isWithinRadius(
        (float)$request->latitude,
        (float)$request->longitude,
        (float)$settings->hotel_latitude,
        (float)$settings->hotel_longitude,
        (int)$settings->allowed_radius_meters
    );
}
//     private function isAllowedIp(Request $request): bool
// {
//     $settings = \App\Models\AttendanceSetting::first();

//     if (!$settings || !$settings->is_ip_check_enabled) {
//         return true;
//     }

//     return $request->ip() === $settings->hotel_wifi_ip;
// }
    public function status(Request $request)
    {
        $user = $request->user();

        $todayLog = AttendanceLog::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'status' => $todayLog?->status ?? 'clocked_out',
            'log' => $todayLog,
        ]);
    }

    public function clockIn(Request $request)
    {
       if (!$this->isAllowedIp($request)) {
    return response()->json([
        'success' => false,
        'message' => 'You must be connected to hotel Wi-Fi.',
    ], 403);
}

if (!$this->isAllowedLocation($request)) {
    return response()->json([
        'success' => false,
        'message' => 'You are outside allowed hotel radius.',
    ], 403);
}

        $user = $request->user();

        $existingLog = AttendanceLog::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->where('status', 'clocked_in')
            ->first();

        if ($existingLog) {
            return response()->json([
                'success' => false,
                'message' => 'You are already clocked in.',
            ], 422);
        }

        $log = AttendanceLog::create([
            'user_id' => $user->id,
            'attendance_date' => today(),
            'clock_in_at' => Carbon::now(),
            'clock_in_ip' => $request->ip(),
            'status' => 'clocked_in',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clocked in successfully.',
            'log' => $log,
        ]);
    }

    public function clockOut(Request $request)
    {
        if (!$this->isAllowedIp($request)) {
    return response()->json([
        'success' => false,
        'message' => 'You must be connected to hotel Wi-Fi.',
    ], 403);
}

if (!$this->isAllowedLocation($request)) {
    return response()->json([
        'success' => false,
        'message' => 'You are outside allowed hotel radius.',
    ], 403);
}

        $user = $request->user();

        $log = AttendanceLog::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->where('status', 'clocked_in')
            ->latest()
            ->first();

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'You are not clocked in.',
            ], 422);
        }

        $log->update([
            'clock_out_at' => Carbon::now(),
            'clock_out_ip' => $request->ip(),
            'status' => 'clocked_out',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clocked out successfully.',
            'log' => $log,
        ]);
    }

    public function history(Request $request)
    {
        $logs = AttendanceLog::where('user_id', $request->user()->id)
            ->latest()
            ->take(30)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }
}
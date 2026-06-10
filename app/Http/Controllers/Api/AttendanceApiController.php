<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\StaffWorkedMoreThan12HoursMail;

class AttendanceApiController extends Controller
{
public function scanQr(Request $request)
{
    $request->validate([
        'token' => 'required|string',
    ]);

    $user = $request->user();

    $qrToken = \App\Models\AttendanceQrToken::where('token', $request->token)
        ->where('expires_at', '>', now())
        ->first();

    if (!$qrToken) {
        return response()->json([
            'success' => false,
            'message' => 'QR code expired. Please scan the current QR.',
        ], 403);
    }

    $activeLog = AttendanceLog::where('user_id', $user->id)
        ->where('status', 'clocked_in')
        ->latest()
        ->first();

    if (!$activeLog) {
        $log = AttendanceLog::create([
            'user_id' => $user->id,
            'attendance_date' => today(),
            'clock_in_at' => now(),
            'clock_in_qr_token_id' => $qrToken->id,
            'status' => 'clocked_in',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clocked in successfully.',
            'status' => 'clocked_in',
            'log' => $log,
        ]);
    }

    $activeLog->update([
        'clock_out_at' => now(),
        'clock_out_qr_token_id' => $qrToken->id,
        'status' => 'clocked_out',
    ]);

    // 12 hour check
    $workedHours = Carbon::parse($activeLog->clock_in_at)
        ->diffInHours(now());

    // if ($workedHours > 12 && !$activeLog->manager_alert_sent) {
    //     Mail::to($managerEmail)->send(new StaffWorkedMoreThan12HoursMail($activeLog));

    //     $activeLog->update([
    //         'manager_alert_sent' => true,
    //     ]);
    // }

    return response()->json([
        'success' => true,
        'message' => 'Clocked out successfully.',
        'status' => 'clocked_out',
        'log' => $activeLog->fresh(),
    ]);
}

public function status(Request $request)
{
    $user = $request->user();

    $log = AttendanceLog::where('user_id', $user->id)
        ->whereNull('clock_out_at')
        ->latest()
        ->first();

    if (!$log) {
        return response()->json([
            'is_clocked_in' => false,
            'clock_in_at' => null,
        ]);
    }

    return response()->json([
        'is_clocked_in' => true,
        'clock_in_at' => $log->clock_in_at,
    ]);
}

}
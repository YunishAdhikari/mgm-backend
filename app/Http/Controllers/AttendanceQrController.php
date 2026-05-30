<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\AttendanceQrToken;

class AttendanceQrController extends Controller
{
    public function generate()
    {
        AttendanceQrToken::query()->delete();

        $token = AttendanceQrToken::create([
            'token' => Str::random(64),
            'expires_at' => now()->addSeconds(30),
        ]);

        return response()->json([
            'token' => $token->token,
            'expires_at' => $token->expires_at,
        ]);

        
    }

    public function screen()
{
    return view('attendance.live-qr');
}
}
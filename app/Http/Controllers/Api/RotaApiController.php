<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RotaShift;
use Illuminate\Http\Request;

class RotaApiController extends Controller
{
    public function myRota(Request $request)
    {
        $shifts = RotaShift::with(['department'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'published')
            ->orderBy('shift_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'shifts' => $shifts,
        ]);
    }
}
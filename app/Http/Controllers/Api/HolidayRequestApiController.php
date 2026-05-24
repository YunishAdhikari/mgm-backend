<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HolidayRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayRequestApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $totalDays = $startDate->diffInDays($endDate) + 1;

        $holiday = HolidayRequest::create([
            'user_id' => $user->id,
            'department_id' => $user->department_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Holiday request submitted successfully.',
            'holiday' => $holiday,
        ], 201);
    }

    public function myRequests(Request $request)
    {
        $requests = HolidayRequest::with(['department', 'approver'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'requests' => $requests,
        ]);
    }

    public function destroy($id)
{
    $request = HolidayRequest::where('id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$request) {
        return response()->json([
            'success' => false,
            'message' => 'Holiday request not found.'
        ], 404);
    }

    if ($request->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Only pending requests can be deleted.'
        ], 403);
    }

    $request->delete();

    return response()->json([
        'success' => true,
        'message' => 'Holiday request deleted successfully.'
    ]);
}
}
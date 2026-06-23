<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintApiController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['department'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'complaints' => $complaints,
        ]);
    }


    public function staffSubmit(Request $request)
{
    $data = $request->validate([
        'guest_name' => 'required|string|max:255',
        'room_number' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:255',

        'category' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required|string',

        'priority' => 'required|in:low,medium,high,urgent',
    ]);

    $complaint = Complaint::create([
    'hotel_id' => auth()->user()->hotel_id,

    'guest_name' => $data['guest_name'],
    'email' => $data['email'] ?? null,
    'phone' => $data['phone'] ?? null,
    'room_number' => $data['room_number'] ?? null,

    'type' => 'complaint',

    'category' => $data['category'],
    'title' => $data['title'],
    'description' => $data['description'],

    'priority' => $data['priority'],
    'status' => 'pending',

    'created_by' => auth()->id(),
]);

    return response()->json([
        'success' => true,
        'message' => 'Complaint submitted successfully.',
        'complaint_id' => $complaint->id,
    ]);
}
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

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
}
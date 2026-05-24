<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffApiController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'department'])
            ->where('status', 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}
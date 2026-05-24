<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if (!$user->role) {
        return redirect('/profile')
            ->with('error', 'Please contact administrator to assign your role.');
    }

    $roleSlug = strtolower($user->role->slug ?? '');
    $roleName = strtolower($user->role->name ?? '');

    // Admin
    if ($roleSlug === 'admin' || $roleName === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Manager
    if ($roleSlug === 'manager' || $roleName === 'manager') {
        return redirect()->route('manager.dashboard');
    }

    // Head Chef
    if ($roleSlug === 'head-chef' || $roleName === 'head chef') {
        return redirect()->route('kitchen.supervisor.dashboard');
    }

    // Supervisor
    if ($roleSlug === 'supervisor' || $roleName === 'supervisor') {
        return redirect()->route('supervisor.dashboard');
    }

    abort(403, 'No valid dashboard assigned.');
}
}
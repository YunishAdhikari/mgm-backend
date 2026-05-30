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

        $departmentSlug = strtolower($user->department->slug ?? '');
        $departmentName = strtolower($user->department->name ?? '');

        // Admin
        if ($roleSlug === 'admin' || $roleName === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Manager
        if ($roleSlug === 'manager' || $roleName === 'manager') {
            return redirect()->route('manager.dashboard');
        }

        // Reception Supervisor
        if (
            in_array($departmentSlug, ['reception', 'front-office']) ||
            in_array($departmentName, ['reception', 'front office'])
        ) {
            if (
                in_array($roleSlug, ['supervisor', 'reception-supervisor']) ||
                in_array($roleName, ['supervisor', 'reception supervisor'])
            ) {
                return redirect()->route('supervisor.dashboard');
            }

            return redirect()->route('reception.dashboard');
        }

        // F&B Supervisor
        if (
            in_array($departmentSlug, ['fb', 'f-b', 'f-and-b', 'food-and-beverage']) ||
            in_array($departmentName, ['fb', 'f&b', 'f and b', 'food and beverage'])
        ) {
            if (
                in_array($roleSlug, ['supervisor', 'fb-supervisor', 'f-and-b-supervisor']) ||
                in_array($roleName, ['supervisor', 'fb supervisor', 'f&b supervisor', 'f and b supervisor'])
            ) {
                return redirect()->route('fb.supervisor.dashboard');
            }

            return redirect()->route('fb.dashboard');
        }

        // Kitchen
        if (
            in_array($departmentSlug, ['kitchen']) ||
            in_array($departmentName, ['kitchen']) ||
            in_array($roleSlug, ['head-chef', 'kitchen-supervisor']) ||
            in_array($roleName, ['head chef', 'kitchen supervisor'])
        ) {
            return redirect()->route('kitchen.supervisor.dashboard');
        }

        // General Supervisor
        if ($roleSlug === 'supervisor' || $roleName === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        abort(403, 'No valid dashboard assigned.');
    }
}
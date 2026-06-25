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

        // DoP / Director of Operations
            if (
                in_array($roleSlug, ['dop', 'director-of-operations', 'director-operations']) ||
                in_array($roleName, ['dop', 'director-of-operations', 'director operations'])
            ) {
                return redirect()->route('dop.dashboard');
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
            in_array($roleSlug, ['Head-chef', 'kitchen-supervisor']) ||
            in_array($roleName, ['Head chef', 'kitchen supervisor'])
        ) {
            return redirect()->route('kitchen.supervisor.dashboard');
        }


        // Housekeeping Supervisor
            if (
                in_array($departmentSlug, ['housekeeping', 'hk', 'house-keeping']) ||
                in_array($departmentName, ['housekeeping', 'hk', 'house keeping'])
            ) {
                if (
                    in_array($roleSlug, ['supervisor', 'housekeeping-supervisor', 'hk-supervisor']) ||
                    in_array($roleName, ['supervisor', 'housekeeping supervisor', 'hk supervisor'])
                ) {
                    return redirect()->route('housekeeping-supervisor.dashboard');
                }
            }

        // General Supervisor
        if ($roleSlug === 'supervisor' || $roleName === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        abort(403, 'No valid dashboard assigned.');
    }
}
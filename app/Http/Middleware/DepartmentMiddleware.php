<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentMiddleware
{
    public function handle(Request $request, Closure $next, ...$departments): Response
    {
        $user = auth()->user();

        if (!$user || !$user->department) {
            abort(403, 'Department access denied.');
        }

        $departmentName = strtolower($user->department->name ?? '');
        $departmentSlug = strtolower($user->department->slug ?? '');

        $allowedDepartments = array_map('strtolower', $departments);

        if (
            !in_array($departmentName, $allowedDepartments) &&
            !in_array($departmentSlug, $allowedDepartments)
        ) {
            abort(403, 'You do not have permission to access this department.');
        }

        return $next($request);
    }
}
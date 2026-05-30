<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(403, 'Unauthorized access.');
        }

        $userRoleName = strtolower($user->role->name ?? '');
        $userRoleSlug = strtolower($user->role->slug ?? '');

        $allowedRoles = array_map('strtolower', $roles);

        if (
            !in_array($userRoleName, $allowedRoles) &&
            !in_array($userRoleSlug, $allowedRoles)
        ) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
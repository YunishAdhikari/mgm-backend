<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartment
{
    public function handle(Request $request, Closure $next, string $department = null): Response
    {
        $user = $request->user();

        if (!$user || !$user->department) {
            abort(403, 'User does not belong to any department.');
        }

        if ($department && $user->department->slug !== $department) {
            abort(403, 'You do not have access to this department.');
        }

        return $next($request);
    }
}
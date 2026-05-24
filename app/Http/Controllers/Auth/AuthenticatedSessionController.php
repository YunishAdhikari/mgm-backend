<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 👇👇👇 CHECK FOR INACTIVE USER 👇👇👇
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'inactive') {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact the administrator.'],
            ]);
        }
        // 👆👆👆 CHECK FOR INACTIVE USER 👆👆👆

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            return redirect()->to($this->redirectToRole(Auth::user()));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    protected function redirectToRole($user)
    {
        if (!$user || !$user->role) {
            return '/dashboard';
        }

        return match ($user->role->slug) {
            'admin' => '/admin/dashboard',
            'manager' => '/manager/dashboard',
            'kitchen-supervisor' => '/kitchen-supervisor/dashboard',
            'supervisor' => '/supervisor/dashboard',
            default => '/dashboard',
        };
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Your login view
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and status is inactive
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'inactive') {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact the administrator.'],
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on role
            return $this->redirectToRole(Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    protected function redirectToRole($user)
    {
        // Assuming you have role relationship
        $roleSlug = $user->role->slug ?? 'staff';

        return match ($roleSlug) {
            'admin' => '/admin/dashboard',
            'manager' => '/manager/dashboard',
            'kitchen-supervisor' => '/kitchen-supervisor/dashboard',
            'supervisor' => '/supervisor/dashboard',
            default => '/dashboard',
        };
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
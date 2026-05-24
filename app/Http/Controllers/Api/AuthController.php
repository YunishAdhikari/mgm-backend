<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Check credentials first
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    $user = Auth::user();

    //CHECK FOR INACTIVE USER
    if ($user->status === 'inactive') {
        Auth::logout(); // Logout the user
        
        return response()->json([
            'success' => false,
            'message' => 'Your account is inactive. Please contact the administrator.',
        ], 403);
    }

    $token = $user->createToken('mgm-ops-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => $user->load(['role', 'department']),
    ]);
}
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     $user = Auth::user();

    //     $token = $user->createToken('mgm-ops-token')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'token' => $token,
    //         'user' => $user->load(['role', 'department']),
    //     ]);
    // }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load(['role', 'department']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Current password is incorrect.',
        ], 422);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Password changed successfully.',
    ]);
}

public function updateProfile(Request $request)
{
    $request->validate([
        'phone' => 'nullable|string|max:30',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user = $request->user();

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('users', 'public');
        $user->image = $imagePath;
    }

    $user->phone = $request->phone;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully.',
        'user' => $user->load(['role', 'department']),
    ]);
}
}
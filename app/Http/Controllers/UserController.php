<?php

namespace App\Http\Controllers;

use App\Support\MockData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', ['users' => MockData::allUsers()]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:120',
            'email' => 'required|email',
            'role'  => 'required|in:Admin,Staff,Resident,Contractor',
        ]);

        // Mock: pretend we saved and flash a message.
        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User \"' . $request->name . '\" created successfully.');
    }
}
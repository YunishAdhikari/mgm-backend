<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
   public function index()
{
    $hotels = Hotel::withCount([
        'users',
        'rooms',
        'departments',
    ])
    ->latest()
    ->paginate(10);

    return view('dashboard.admin.hotels.index', compact('hotels'));
}

    public function create()
    {
        return view('dashboard.admin.hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:hotels,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(Str::slug($validated['name'], '-'));
        }

        if ($request->hasFile('logo')) {
            $logoName = time() . '_' . uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/hotels'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active');

        Hotel::create($validated);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully.');
    }

    public function show(Hotel $hotel)
    {
        return view('dashboard.admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        return view('dashboard.admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:hotels,code,' . $hotel->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($hotel->logo && file_exists(public_path('uploads/hotels/' . $hotel->logo))) {
                unlink(public_path('uploads/hotels/' . $hotel->logo));
            }

            $logoName = time() . '_' . uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/hotels'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active');

        $hotel->update($validated);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->update([
            'is_active' => ! $hotel->is_active,
        ]);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', $hotel->is_active ? 'Hotel activated.' : 'Hotel deactivated.');
    }


    public function setup(Hotel $hotel)
{
    $hotel->loadCount([
        'users',
        'departments',
    ]);

    return view('dashboard.admin.hotels.setup', compact('hotel'));
}
}
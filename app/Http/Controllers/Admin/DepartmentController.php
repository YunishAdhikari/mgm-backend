<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Hotel;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $departments = Department::with(['hotel'])
            ->withCount('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                        $hotelQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $hotels = Hotel::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.departments.index', compact(
            'departments',
            'hotels',
            'search'
        ));
    }

    public function create()
    {
        $hotels = Hotel::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.departments.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
        ]);

        $exists = Department::where('hotel_id', $request->hotel_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This department already exists for the selected hotel.',
                ]);
        }

        Department::create([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $hotels = Hotel::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.departments.edit', compact(
            'department',
            'hotels'
        ));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
        ]);

        $exists = Department::where('hotel_id', $request->hotel_id)
            ->where('name', $request->name)
            ->where('id', '!=', $department->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This department already exists for the selected hotel.',
                ]);
        }

        $department->update([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return back()->withErrors([
                'delete' => 'This department cannot be deleted because employees are assigned to it.',
            ]);
        }

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
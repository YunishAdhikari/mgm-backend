<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForecastGroup;
use Illuminate\Http\Request;

class ForecastGroupController extends Controller
{
    public function index()
    {
        $groups = ForecastGroup::latest()->get();

        return view('dashboard.admin.forecast-groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forecast_groups,name',
            'description' => 'nullable|string',
        ]);

        ForecastGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Forecast group added successfully.');
    }

    public function update(Request $request, ForecastGroup $forecastGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forecast_groups,name,' . $forecastGroup->id,
            'description' => 'nullable|string',
        ]);

        $forecastGroup->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Forecast group updated successfully.');
    }

    public function destroy(ForecastGroup $forecastGroup)
    {
        $forecastGroup->delete();

        return back()->with('success', 'Forecast group deleted successfully.');
    }
}
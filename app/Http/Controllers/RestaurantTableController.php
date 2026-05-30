<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::orderBy('position_y')
            ->orderBy('position_x')
            ->get();

        return view('dashboard.admin.restaurant.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_name' => 'required|string|max:255|unique:restaurant_tables,table_name',
            'capacity' => 'required|integer|min:1',
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'table_shape' => 'required|in:round,square,horizontal,vertical,banquet',
            'status' => 'required|in:available,reserved,occupied,out_of_service',
        ]);

        RestaurantTable::create([
            'table_name' => $data['table_name'],
            'capacity' => $data['capacity'],
            'position_x' => $data['position_x'],
            'position_y' => $data['position_y'],
            'is_active' => $request->boolean('is_active'),
            'table_shape' => $data['table_shape'],
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Restaurant table created successfully.');
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $data = $request->validate([
            'table_name' => 'required|string|max:255|unique:restaurant_tables,table_name,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'table_shape' => 'required|in:round,square,horizontal,vertical,banquet',
            'status' => 'required|in:available,reserved,occupied,out_of_service',
        ]);

        $table->update([
            'table_name' => $data['table_name'],
            'capacity' => $data['capacity'],
            'position_x' => $data['position_x'],
            'position_y' => $data['position_y'],
            'is_active' => $request->boolean('is_active'),
            'table_shape' => $data['table_shape'],
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Restaurant table updated successfully.');
    }

    public function destroy(RestaurantTable $table)
    {
        $table->delete();

        return back()->with('success', 'Restaurant table deleted successfully.');
    }

    public function floorPlan()
{
    $tables = RestaurantTable::where('is_active', true)->get();

    return view('dashboard.admin.restaurant.tables.floor-plan', compact('tables'));
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\RestaurantFloorObject;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;


class RestaurantTableController extends Controller
{
    public function index(Hotel $hotel, Restaurant $restaurant)
    {
        $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

        $tables = $restaurant->tables()
            ->orderBy('position_y')
            ->orderBy('position_x')
            ->get();

        return view('dashboard.admin.restaurants.tables.index', compact(
            'hotel',
            'restaurant',
            'tables'
        ));
    }

    public function store(Request $request, Hotel $hotel, Restaurant $restaurant)
    {
        $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

        $data = $request->validate([
            'table_name' => 'required|string|max:255|unique:restaurant_tables,table_name,NULL,id,restaurant_id,' . $restaurant->id,
            'capacity' => 'required|integer|min:1',
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'table_shape' => 'required|in:round,square,horizontal,vertical,banquet',
            'status' => 'required|in:available,reserved,occupied,out_of_service',
        ]);

        RestaurantTable::create([
            'restaurant_id' => $restaurant->id,
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
            'table_name' => 'required|string|max:255|unique:restaurant_tables,table_name,' . $table->id . ',id,restaurant_id,' . $table->restaurant_id,
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

public function floorPlan(Hotel $hotel, Restaurant $restaurant)
{
    $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

    $tables = $restaurant->tables()
        ->where('is_active', true)
        ->orderBy('position_y')
        ->orderBy('position_x')
        ->get();

    $objects = $restaurant->floorObjects()
        ->where('is_active', true)
        ->get();

    return view('dashboard.admin.restaurants.tables.floor-plan', compact(
        'hotel',
        'restaurant',
        'tables',
        'objects'
    ));
}public function storeFloorObject(Request $request, Hotel $hotel, Restaurant $restaurant)
{
    $this->ensureRestaurantBelongsToHotel($hotel, $restaurant);

    $data = $request->validate([
        'type' => 'required|in:wall,door,window,bar,buffet,cashier,toilet,plant,sofa,note',
    ]);

    $object = RestaurantFloorObject::create([
        'restaurant_id' => $restaurant->id,
        'type' => $data['type'],
        'label' => ucfirst($data['type']),
        'position_x' => 80,
        'position_y' => 80,
        'width' => $data['type'] === 'wall' ? 220 : 120,
        'height' => $data['type'] === 'wall' ? 24 : 80,
        'rotation' => 0,
        'is_active' => true,
    ]);

    return back()->with('success', ucfirst($object->type) . ' added to floor plan.');
}

public function updateFloorObjectPosition(Request $request, RestaurantFloorObject $object)
{
    $data = $request->validate([
        'position_x' => 'required|integer|min:0',
        'position_y' => 'required|integer|min:0',
        'width' => 'nullable|integer|min:20',
        'height' => 'nullable|integer|min:20',
        'rotation' => 'nullable|integer|min:0|max:360',
    ]);

    $object->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Object updated.',
    ]);
}

public function destroyFloorObject(RestaurantFloorObject $object)
{
    $object->delete();

    return back()->with('success', 'Floor object removed.');
}
    public function updatePosition(Request $request, RestaurantTable $table)
{
    $data = $request->validate([
        'position_x' => 'required|integer|min:0',
        'position_y' => 'required|integer|min:0',
    ]);

    $table->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Table position updated.',
    ]);
}

    private function ensureRestaurantBelongsToHotel(Hotel $hotel, Restaurant $restaurant): void
    {
        if ((int) $restaurant->hotel_id !== (int) $hotel->id) {
            abort(404);
        }
    }
}
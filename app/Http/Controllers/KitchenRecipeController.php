<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\MenuItem;
use App\Models\RecipeIngredient;
use Illuminate\Http\Request;

class KitchenRecipeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $menuItems = MenuItem::with('ingredients.inventoryItem')
            ->where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $inventoryItems = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.kitchen-supervisor.recipes.index',
            compact('menuItems', 'inventoryItems')
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'nullable|numeric|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        foreach ($request->ingredients as $ingredient) {

            $exists = InventoryItem::where('id', $ingredient['inventory_item_id'])
                ->where('hotel_id', $user->hotel_id)
                ->where('department_id', $user->department_id)
                ->exists();

            if (!$exists) {
                abort(403, 'Invalid inventory item.');
            }
        }

        $menuItem = MenuItem::create([
            'hotel_id' => $user->hotel_id,
            'department_id' => $user->department_id,
            'name' => $request->name,
            'description' => $request->description,
            'selling_price' => $request->selling_price,
            'is_active' => true,
        ]);

        foreach ($request->ingredients as $ingredient) {

            RecipeIngredient::create([
                'hotel_id' => $user->hotel_id,
                'menu_item_id' => $menuItem->id,
                'inventory_item_id' => $ingredient['inventory_item_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return back()->with('success', 'Recipe added successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->checkAccess($menuItem);

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'nullable|numeric|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        foreach ($request->ingredients as $ingredient) {

            $exists = InventoryItem::where('id', $ingredient['inventory_item_id'])
                ->where('hotel_id', $user->hotel_id)
                ->where('department_id', $user->department_id)
                ->exists();

            if (!$exists) {
                abort(403, 'Invalid inventory item.');
            }
        }

        $menuItem->update([
            'name' => $request->name,
            'description' => $request->description,
            'selling_price' => $request->selling_price,
        ]);

        $menuItem->ingredients()->delete();

        foreach ($request->ingredients as $ingredient) {

            RecipeIngredient::create([
                'hotel_id' => $user->hotel_id,
                'menu_item_id' => $menuItem->id,
                'inventory_item_id' => $ingredient['inventory_item_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return back()->with('success', 'Recipe updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->checkAccess($menuItem);

        $menuItem->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Recipe removed successfully.');
    }

    public function currentRecipes()
    {
        $user = auth()->user();

        $menuItems = MenuItem::with('ingredients.inventoryItem')
            ->where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $inventoryItems = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.kitchen-supervisor.recipes.current',
            compact('menuItems', 'inventoryItems')
        );
    }

    private function checkAccess(MenuItem $menuItem): void
    {
        $user = auth()->user();

        if (
            (int)$menuItem->hotel_id !== (int)$user->hotel_id ||
            (int)$menuItem->department_id !== (int)$user->department_id
        ) {
            abort(403, 'You are not allowed to access this recipe.');
        }
    }
}
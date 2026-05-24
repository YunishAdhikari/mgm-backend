<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\InventoryItem;
use App\Models\RecipeIngredient;
use Illuminate\Http\Request;

class KitchenRecipeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $menuItems = MenuItem::with('ingredients.inventoryItem')
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $inventoryItems = InventoryItem::where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.kitchen-supervisor.recipes.index', compact(
            'menuItems',
            'inventoryItems'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'nullable|numeric|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $menuItem = MenuItem::create([
            'department_id' => auth()->user()->department_id,
            'name' => $request->name,
            'description' => $request->description,
            'selling_price' => $request->selling_price,
            'is_active' => true,
        ]);

        foreach ($request->ingredients as $ingredient) {
            RecipeIngredient::create([
                'menu_item_id' => $menuItem->id,
                'inventory_item_id' => $ingredient['inventory_item_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return back()->with('success', 'Recipe added successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->department_id !== auth()->user()->department_id) {
            abort(403);
        }

        $menuItem->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Recipe removed successfully.');
    }

    //update recipe
    public function update(Request $request, MenuItem $menuItem)
{
    if ($menuItem->department_id !== auth()->user()->department_id) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'selling_price' => 'nullable|numeric|min:0',
        'ingredients' => 'required|array|min:1',
        'ingredients.*.inventory_item_id' => 'required|exists:inventory_items,id',
        'ingredients.*.quantity' => 'required|numeric|min:0.01',
    ]);

    $menuItem->update([
        'name' => $request->name,
        'description' => $request->description,
        'selling_price' => $request->selling_price,
    ]);

    $menuItem->ingredients()->delete();

    foreach ($request->ingredients as $ingredient) {
        RecipeIngredient::create([
            'menu_item_id' => $menuItem->id,
            'inventory_item_id' => $ingredient['inventory_item_id'],
            'quantity' => $ingredient['quantity'],
        ]);
    }

    return back()->with('success', 'Recipe updated successfully.');
}

public function currentRecipes()
{
    $menuItems = MenuItem::with('ingredients.inventoryItem')
        ->where('department_id', auth()->user()->department_id)
        ->where('is_active', true)
        ->latest()
        ->get();

    $inventoryItems = InventoryItem::where('department_id', auth()->user()->department_id)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('dashboard.kitchen-supervisor.recipes.current', compact(
        'menuItems',
        'inventoryItems'
    ));
}
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BuffetMenu;
use App\Models\BuffetSale;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenMobileController extends Controller
{
    private function checkKitchenAccess($user)
    {
        return strtolower($user->department->name ?? '') === 'kitchen'
            && in_array(strtolower($user->role->name ?? ''), ['supervisor', 'chef']);
    }

    public function stockIn(Request $request)
    {
        $user = $request->user();

        if (!$this->checkKitchenAccess($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        $item = InventoryItem::where('id', $request->inventory_item_id)
            ->where('department_id', $user->department_id)
            ->firstOrFail();

        $item->quantity += $request->quantity;
        $item->save();

        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'user_id' => $user->id,
            'type' => 'stock_in',
            'quantity' => $request->quantity,
            'note' => $request->note ?? 'Mobile stock in',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully.',
        ]);
    }

    public function recipes(Request $request)
    {
        $user = $request->user();

        if (!$this->checkKitchenAccess($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $recipes = MenuItem::with('ingredients.inventoryItem')
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'recipes' => $recipes,
        ]);
    }

    public function recipeSale(Request $request, MenuItem $menuItem)
    {
        $user = $request->user();

        if (!$this->checkKitchenAccess($user) || $menuItem->department_id !== $user->department_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $menuItem, $user) {
            $menuItem->load('ingredients.inventoryItem');

            foreach ($menuItem->ingredients as $ingredient) {
                $inventoryItem = $ingredient->inventoryItem;
                $deductQuantity = $ingredient->quantity * $request->quantity;

                $inventoryItem->quantity = max(0, $inventoryItem->quantity - $deductQuantity);
                $inventoryItem->save();

                InventoryTransaction::create([
                    'inventory_item_id' => $inventoryItem->id,
                    'user_id' => $user->id,
                    'type' => 'stock_out',
                    'quantity' => $deductQuantity,
                    'note' => 'Recipe sale: ' . $menuItem->name . ' x ' . $request->quantity,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Sale recorded and inventory deducted.',
        ]);
    }

    public function buffets(Request $request)
    {
        $user = $request->user();

        if (!$this->checkKitchenAccess($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $buffets = BuffetMenu::with('items.menuItem.ingredients.inventoryItem')
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'buffets' => $buffets,
        ]);
    }

    public function buffetSale(Request $request, BuffetMenu $buffetMenu)
    {
        $user = $request->user();

        if (!$this->checkKitchenAccess($user) || $buffetMenu->department_id !== $user->department_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'sale_date' => 'required|date',
            'pax' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $buffetMenu, $user) {
            BuffetSale::create([
                'buffet_menu_id' => $buffetMenu->id,
                'user_id' => $user->id,
                'sale_date' => $request->sale_date,
                'pax' => $request->pax,
                'note' => $request->note,
            ]);

            $buffetMenu->load('items.menuItem.ingredients.inventoryItem');

            foreach ($buffetMenu->items as $buffetItem) {
                foreach ($buffetItem->menuItem->ingredients as $ingredient) {
                    $inventoryItem = $ingredient->inventoryItem;
                    $deductQuantity = $ingredient->quantity * $request->pax;

                    $inventoryItem->quantity = max(0, $inventoryItem->quantity - $deductQuantity);
                    $inventoryItem->save();

                    InventoryTransaction::create([
                        'inventory_item_id' => $inventoryItem->id,
                        'user_id' => $user->id,
                        'type' => 'stock_out',
                        'quantity' => $deductQuantity,
                        'note' => 'Buffet sale: ' . $buffetMenu->name . ' (' . $request->pax . ' pax)',
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Buffet sale recorded and inventory deducted.',
        ]);
    }

    public function wastage(Request $request)
{
    $user = $request->user();

    if (!$this->checkKitchenAccess($user)) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'inventory_item_id' => 'required|exists:inventory_items,id',
        'quantity' => 'required|numeric|min:0.01',
        'reason' => 'nullable|string|max:255',
        'note' => 'nullable|string',
    ]);

    $item = InventoryItem::where('id', $request->inventory_item_id)
        ->where('department_id', $user->department_id)
        ->firstOrFail();

    DB::transaction(function () use ($request, $item, $user) {
        $item->quantity = max(0, $item->quantity - $request->quantity);
        $item->save();

        \App\Models\InventoryWastage::create([
            'inventory_item_id' => $item->id,
            'user_id' => $user->id,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'note' => $request->note,
        ]);

        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'user_id' => $user->id,
            'type' => 'stock_out',
            'quantity' => $request->quantity,
            'note' => 'Mobile wastage: ' . ($request->reason ?? 'No reason'),
        ]);
    });

    return response()->json([
        'success' => true,
        'message' => 'Wastage recorded and inventory deducted.',
    ]);
}
}
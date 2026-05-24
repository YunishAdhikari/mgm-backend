<?php

namespace App\Http\Controllers;

use App\Models\BuffetMenu;
use App\Models\BuffetMenuItem;
use App\Models\BuffetSale;
use App\Models\InventoryTransaction;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenBuffetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $buffets = BuffetMenu::with('items.menuItem.ingredients.inventoryItem')
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $menuItems = MenuItem::where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.kitchen-supervisor.buffets.index', compact(
            'buffets',
            'menuItems'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'menu_items' => 'required|array|min:1',
            'menu_items.*' => 'required|exists:menu_items,id',
        ]);

        $buffet = BuffetMenu::create([
            'department_id' => auth()->user()->department_id,
            'name' => $request->name,
            'service_type' => $request->service_type,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        foreach ($request->menu_items as $menuItemId) {
            BuffetMenuItem::create([
                'buffet_menu_id' => $buffet->id,
                'menu_item_id' => $menuItemId,
            ]);
        }

        return back()->with('success', 'Buffet menu created successfully.');
    }

    public function storeSale(Request $request, BuffetMenu $buffetMenu)
    {
        if ($buffetMenu->department_id !== auth()->user()->department_id) {
            abort(403);
        }

        $request->validate([
            'sale_date' => 'required|date',
            'pax' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $buffetMenu) {
            BuffetSale::create([
                'buffet_menu_id' => $buffetMenu->id,
                'user_id' => auth()->id(),
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
                        'user_id' => auth()->id(),
                        'type' => 'stock_out',
                        'quantity' => $deductQuantity,
                        'note' => 'Buffet sale: ' . $buffetMenu->name . ' (' . $request->pax . ' pax)',
                    ]);
                }
            }
        });

        return back()->with('success', 'Buffet sale recorded and inventory deducted successfully.');
    }
}
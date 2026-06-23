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
            ->where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $menuItems = MenuItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
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
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'menu_items' => 'required|array|min:1',
            'menu_items.*' => 'required|exists:menu_items,id',
        ]);

        foreach ($request->menu_items as $menuItemId) {
            $exists = MenuItem::where('id', $menuItemId)
                ->where('hotel_id', $user->hotel_id)
                ->where('department_id', $user->department_id)
                ->where('is_active', true)
                ->exists();

            if (!$exists) {
                abort(403, 'Invalid menu item selected.');
            }
        }

        DB::transaction(function () use ($request, $user) {
            $buffet = BuffetMenu::create([
                'hotel_id' => $user->hotel_id,
                'department_id' => $user->department_id,
                'name' => $request->name,
                'service_type' => $request->service_type,
                'notes' => $request->notes,
                'is_active' => true,
            ]);

            foreach ($request->menu_items as $menuItemId) {
                BuffetMenuItem::create([
                    'hotel_id' => $user->hotel_id,
                    'buffet_menu_id' => $buffet->id,
                    'menu_item_id' => $menuItemId,
                ]);
            }
        });

        return back()->with('success', 'Buffet menu created successfully.');
    }

    public function update(Request $request, BuffetMenu $buffetMenu)
    {
        $this->checkAccess($buffetMenu);

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'menu_items' => 'required|array|min:1',
            'menu_items.*' => 'required|exists:menu_items,id',
        ]);

        foreach ($request->menu_items as $menuItemId) {
            $exists = MenuItem::where('id', $menuItemId)
                ->where('hotel_id', $user->hotel_id)
                ->where('department_id', $user->department_id)
                ->where('is_active', true)
                ->exists();

            if (!$exists) {
                abort(403, 'Invalid menu item selected.');
            }
        }

        DB::transaction(function () use ($request, $buffetMenu, $user) {
            $buffetMenu->update([
                'name' => $request->name,
                'service_type' => $request->service_type,
                'notes' => $request->notes,
            ]);

            $buffetMenu->items()->delete();

            foreach ($request->menu_items as $menuItemId) {
                BuffetMenuItem::create([
                    'hotel_id' => $user->hotel_id,
                    'buffet_menu_id' => $buffetMenu->id,
                    'menu_item_id' => $menuItemId,
                ]);
            }
        });

        return back()->with('success', 'Buffet menu updated successfully.');
    }

    public function destroy(BuffetMenu $buffetMenu)
    {
        $this->checkAccess($buffetMenu);

        $buffetMenu->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Buffet menu removed successfully.');
    }

    public function storeSale(Request $request, BuffetMenu $buffetMenu)
    {
        $this->checkAccess($buffetMenu);

        $user = auth()->user();

        $request->validate([
            'sale_date' => 'required|date',
            'pax' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $buffetMenu, $user) {
            BuffetSale::create([
                'hotel_id' => $user->hotel_id,
                'buffet_menu_id' => $buffetMenu->id,
                'user_id' => $user->id,
                'sale_date' => $request->sale_date,
                'pax' => $request->pax,
                'note' => $request->note,
            ]);

            $buffetMenu->load('items.menuItem.ingredients.inventoryItem');

            foreach ($buffetMenu->items as $buffetItem) {
                if ((int) $buffetItem->hotel_id !== (int) $user->hotel_id) {
                    abort(403, 'Invalid buffet item.');
                }

                foreach ($buffetItem->menuItem->ingredients as $ingredient) {
                    if ((int) $ingredient->hotel_id !== (int) $user->hotel_id) {
                        abort(403, 'Invalid recipe ingredient.');
                    }

                    $inventoryItem = $ingredient->inventoryItem;

                    if (
                        !$inventoryItem ||
                        (int) $inventoryItem->hotel_id !== (int) $user->hotel_id ||
                        (int) $inventoryItem->department_id !== (int) $user->department_id
                    ) {
                        abort(403, 'Invalid inventory item.');
                    }

                    $deductQuantity = $ingredient->quantity * $request->pax;

                    if ($inventoryItem->quantity < $deductQuantity) {
                        throw new \Exception(
                            'Not enough stock for ' . $inventoryItem->name .
                            '. Required: ' . $deductQuantity .
                            ', Available: ' . $inventoryItem->quantity
                        );
                    }

                    $inventoryItem->decrement('quantity', $deductQuantity);

                    InventoryTransaction::create([
                        'hotel_id' => $user->hotel_id,
                        'inventory_item_id' => $inventoryItem->id,
                        'user_id' => $user->id,
                        'type' => 'stock_out',
                        'quantity' => $deductQuantity,
                        'note' => 'Buffet sale: ' . $buffetMenu->name . ' (' . $request->pax . ' pax)',
                    ]);
                }
            }
        });

        return back()->with('success', 'Buffet sale recorded and inventory deducted successfully.');
    }

    private function checkAccess(BuffetMenu $buffetMenu): void
    {
        $user = auth()->user();

        if (
            (int) $buffetMenu->hotel_id !== (int) $user->hotel_id ||
            (int) $buffetMenu->department_id !== (int) $user->department_id
        ) {
            abort(403, 'You are not allowed to access this buffet menu.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\InventoryWastage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenWastageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $items = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $wastages = InventoryWastage::with(['item', 'user'])
            ->where('hotel_id', $user->hotel_id)
            ->whereHas('item', function ($query) use ($user) {
                $query->where('hotel_id', $user->hotel_id)
                    ->where('department_id', $user->department_id);
            })
            ->latest()
            ->get();

        return view(
            'dashboard.kitchen-supervisor.wastage.index',
            compact('items', 'wastages')
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $item = InventoryItem::where('id', $request->inventory_item_id)
            ->where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->firstOrFail();

        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        DB::transaction(function () use ($request, $item, $user) {

            $item->decrement('quantity', $request->quantity);

            InventoryWastage::create([
                'hotel_id' => $user->hotel_id,
                'inventory_item_id' => $item->id,
                'user_id' => $user->id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'note' => $request->note,
            ]);

            InventoryTransaction::create([
                'hotel_id' => $user->hotel_id,
                'inventory_item_id' => $item->id,
                'user_id' => $user->id,
                'type' => 'stock_out',
                'quantity' => $request->quantity,
                'note' => 'Wastage: ' . ($request->reason ?? 'No reason'),
            ]);
        });

        return back()->with('success', 'Wastage recorded and inventory deducted.');
    }
}
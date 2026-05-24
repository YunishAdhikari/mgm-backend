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
        $items = InventoryItem::where('department_id', auth()->user()->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $wastages = InventoryWastage::with(['item', 'user'])
            ->whereHas('item', function ($query) {
                $query->where('department_id', auth()->user()->department_id);
            })
            ->latest()
            ->get();

        return view('dashboard.kitchen-supervisor.wastage.index', compact(
            'items',
            'wastages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $item = InventoryItem::where('id', $request->inventory_item_id)
            ->where('department_id', auth()->user()->department_id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $item) {
            $item->quantity = max(0, $item->quantity - $request->quantity);
            $item->save();

            InventoryWastage::create([
                'inventory_item_id' => $item->id,
                'user_id' => auth()->id(),
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'note' => $request->note,
            ]);

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'user_id' => auth()->id(),
                'type' => 'stock_out',
                'quantity' => $request->quantity,
                'note' => 'Wastage: ' . ($request->reason ?? 'No reason'),
            ]);
        });

        return back()->with('success', 'Wastage recorded and inventory deducted.');
    }
}
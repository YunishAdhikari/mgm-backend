<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KitchenInventoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $items = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $lowStockItems = $items->filter(function ($item) {
            return $item->quantity <= $item->minimum_stock;
        });

        return view('dashboard.kitchen-supervisor.inventory.index', compact(
            'items',
            'lowStockItems'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        InventoryItem::create([
            'hotel_id' => $user->hotel_id,
            'department_id' => $user->department_id,
            'name' => $request->name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock,
            'is_active' => true,
        ]);

        return back()->with('success', 'Inventory item added successfully.');
    }

    public function stockUpdate(Request $request, InventoryItem $item)
    {
        $this->checkAccess($item);

        $request->validate([
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($request->type === 'stock_in') {
            $item->quantity += $request->quantity;
        }

        if ($request->type === 'stock_out') {
            if ($item->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock available.');
            }

            $item->quantity -= $request->quantity;
        }

        if ($request->type === 'adjustment') {
            $item->quantity = $request->quantity;
        }

        $item->save();

        InventoryTransaction::create([
            'hotel_id' => $item->hotel_id,
            'inventory_item_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Stock updated successfully.');
    }

    public function update(Request $request, InventoryItem $item)
    {
        $this->checkAccess($item);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        $item->update([
            'name' => $request->name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock,
        ]);

        return back()->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(InventoryItem $item)
    {
        $this->checkAccess($item);

        $item->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Inventory item removed successfully.');
    }

    public function currentInventory()
    {
        $user = auth()->user();

        $items = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.kitchen-supervisor.inventory.current', compact('items'));
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = InventoryTransaction::with(['item', 'user'])
            ->where('hotel_id', $user->hotel_id)
            ->whereHas('item', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id)
                    ->where('department_id', $user->department_id);
            });

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('item_id')) {
            $query->where('inventory_item_id', $request->item_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->get();

        $items = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.kitchen-supervisor.inventory.history', compact(
            'transactions',
            'items'
        ));
    }

    public function historyPdf(Request $request)
    {
        $user = auth()->user();

        $query = InventoryTransaction::with(['item', 'user'])
            ->where('hotel_id', $user->hotel_id)
            ->whereHas('item', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id)
                    ->where('department_id', $user->department_id);
            });

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('item_id')) {
            $query->where('inventory_item_id', $request->item_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->get();

        $pdf = Pdf::loadView(
            'dashboard.kitchen-supervisor.inventory.history-pdf',
            compact('transactions')
        )->setPaper('a4', 'landscape');

        return $pdf->download('inventory-history-report.pdf');
    }

    private function checkAccess(InventoryItem $item): void
    {
        $user = auth()->user();

        if (
            (int) $item->hotel_id !== (int) $user->hotel_id ||
            (int) $item->department_id !== (int) $user->department_id
        ) {
            abort(403, 'You are not allowed to access this inventory item.');
        }
    }
}
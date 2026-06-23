<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class KitchenInventoryApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $departmentName = strtolower($user->department->name ?? '');
        $roleName = strtolower($user->role->name ?? '');

        if (
            !in_array($departmentName, ['kitchen', 'food and beverage', 'f&b', 'fb']) ||
            !in_array($roleName, ['supervisor', 'chef', 'head chef', 'kitchen supervisor'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $items = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'hotel_id',
                'department_id',
                'name',
                'category',
                'quantity',
                'unit',
                'minimum_stock',
            ])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'hotel_id' => $item->hotel_id,
                    'department_id' => $item->department_id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'minimum_stock' => $item->minimum_stock,
                    'is_low_stock' => $item->quantity <= $item->minimum_stock,
                ];
            });

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }
}
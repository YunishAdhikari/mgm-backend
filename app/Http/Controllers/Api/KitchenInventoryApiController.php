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

        if (
            strtolower($user->department->name ?? '') !== 'kitchen' ||
            !in_array(strtolower($user->role->name ?? ''), ['supervisor', 'chef'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $items = InventoryItem::where('department_id', $user->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'category',
                'quantity',
                'unit',
                'minimum_stock',
            ]);

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }
}
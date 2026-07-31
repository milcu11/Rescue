<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;

class ApiInventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with('creator')
            ->orderBy('status')
            ->orderBy('name')
            ->get();

        $summary = [
            'total'     => $items->count(),
            'available' => $items->where('status', 'available')->count(),
            'low_stock' => $items->where('status', 'low_stock')->count(),
            'depleted'  => $items->where('status', 'depleted')->count(),
        ];

        return response()->json([
            'success' => true,
            'data'    => $items,
            'summary' => $summary,
        ]);
    }

    public function show(int $id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                => $inventoryItem->id,
                'name'              => $inventoryItem->name,
                'category'          => $inventoryItem->category,
                'quantity'          => $inventoryItem->quantity,
                'unit'              => $inventoryItem->unit,
                'minimum_threshold' => $inventoryItem->minimum_threshold,
                'status'            => $inventoryItem->status,
                'location'          => $inventoryItem->location,
                'notes'             => $inventoryItem->notes,
                'created_at'        => $inventoryItem->created_at,
            ],
        ]);
    }
}

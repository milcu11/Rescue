<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class ApiInventoryController extends Controller
{
    protected function buildInventoryListQuery(Request $request, bool $publicOnly = false)
    {
        $query = InventoryItem::query();

        if ($publicOnly) {
            $query->where('quantity', '>', 0);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->boolean('low_stock')) {
            $query->whereIn('status', ['low_stock', 'depleted']);
        }

        return $query->orderBy('category')->orderBy('name');
    }

    protected function formatItem(InventoryItem $item): array
    {
        return [
            'id'             => $item->id,
            'sku'            => $item->sku ?? '',
            'name'           => $item->name,
            'category'       => $item->category,
            'category_label' => $item->category_label,
            'quantity'       => (int) $item->quantity,
            'unit'           => $item->unit,
            'expires_at'     => $item->expires_at?->format('Y-m-d') ?? '',
            'is_low_stock'   => (bool) $item->is_low_stock,
            'updated_at'     => $item->updated_at?->format('Y-m-d H:i') ?? '',
        ];
    }

    public function index(Request $request)
    {
        $limit = max(1, min((int) $request->get('limit', 200), 500));
        $items = $this->buildInventoryListQuery($request)->limit($limit)->get();

        return response()->json([
            'data' => $items->map(fn($item) => $this->formatItem($item)),
            'meta' => [
                'count'     => $items->count(),
                'limit'     => $limit,
                'read_only' => true,
            ],
        ]);
    }

    public function publicIndex(Request $request)
    {
        $limit = max(1, min((int) $request->get('limit', 200), 500));
        $items = $this->buildInventoryListQuery($request, true)->limit($limit)->get();

        return response()->json([
            'data' => $items->map(fn($item) => $this->formatItem($item)),
            'meta' => [
                'count'     => $items->count(),
                'limit'     => $limit,
                'read_only' => true,
            ],
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

    public function publicShow(int $id)
    {
        $inventoryItem = InventoryItem::where('quantity', '>', 0)->findOrFail($id);

        return response()->json([
            'data' => [$this->formatItem($inventoryItem)],
            'meta' => [
                'count'     => 1,
                'limit'     => 1,
                'read_only' => true,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
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

        return view('inventory.index', compact('items', 'summary'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function show(InventoryItem $inventoryItem)
    {
        return redirect()->route('inventory.edit', $inventoryItem);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku'               => 'nullable|string|max:100',
            'name'              => 'required|string|max:255',
            'category'          => 'required|in:food,medicine,clothing,tools,other',
            'quantity'          => 'required|integer|min:0',
            'unit'              => 'required|string|max:50',
            'expires_at'        => 'nullable|date',
            'minimum_threshold' => 'required|integer|min:0',
            'location'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item = InventoryItem::create([
            ...$request->only([
                'sku', 'name', 'category', 'quantity',
                'unit', 'expires_at', 'minimum_threshold', 'warehouse', 'location', 'notes'
            ]),
            'created_by' => Auth::id(),
        ]);

        AuditService::created(
            'inventory',
            $item->name,
            $item->id,
            $item->toArray()
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Item added to inventory successfully.');
    }

    public function edit(InventoryItem $inventoryItem)
    {
        return view('inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $validator = Validator::make($request->all(), [
            'sku'               => 'nullable|string|max:100',
            'name'              => 'required|string|max:255',
            'category'          => 'required|in:food,medicine,clothing,tools,other',
            'quantity'          => 'required|integer|min:0',
            'unit'              => 'required|string|max:50',
            'expires_at'        => 'nullable|date',
            'minimum_threshold' => 'required|integer|min:0',
            'location'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $old = $inventoryItem->toArray();

        $inventoryItem->update($request->only([
            'sku', 'name', 'category', 'quantity',
            'unit', 'expires_at', 'minimum_threshold', 'warehouse', 'location', 'notes'
        ]));

        AuditService::updated(
            'inventory',
            $inventoryItem->name,
            $inventoryItem->id,
            $old,
            $inventoryItem->fresh()->toArray()
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        AuditService::deleted('inventory', $inventoryItem->name, $inventoryItem->id);
        $inventoryItem->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Item removed from inventory.');
    }
}

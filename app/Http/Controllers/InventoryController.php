<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with('creator')
            ->orderByDesc('is_active')
            ->orderBy('status')
            ->orderBy('name')
            ->get();

        $items->each->syncStatus();

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
            'category'          => 'required|in:food,medical,clothing,tools,other,emergency,first_aid,hygiene,water',
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

        if ($item->quantity > 0) {
            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'type' => 'stock_in',
                'quantity' => $item->quantity,
                'quantity_before' => 0,
                'quantity_after' => $item->quantity,
                'reference' => 'INIT-' . Str::upper(Str::random(12)),
                'user_id' => Auth::id(),
                'occurred_at' => now(),
                'source_type' => 'inventory_creation',
                'notes' => 'Opening inventory balance.',
            ]);
        }

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
        $inventoryItem->load(['movements.user']);
        return view('inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $validator = Validator::make($request->all(), [
            'sku'               => 'nullable|string|max:100',
            'name'              => 'required|string|max:255',
            'category'          => 'required|in:food,medical,clothing,tools,other,emergency,first_aid,hygiene,water',
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
        $newQuantity = (int) $request->input('quantity');

        DB::transaction(function () use ($request, $inventoryItem, $newQuantity, $old) {
            $inventoryItem->update($request->only([
                'sku', 'name', 'category', 'quantity',
                'unit', 'expires_at', 'minimum_threshold', 'warehouse', 'location', 'notes'
            ]));

            $delta = $newQuantity - (int) $old['quantity'];
            if ($delta !== 0) {
                InventoryMovement::create([
                    'inventory_item_id' => $inventoryItem->id,
                    'type' => $delta > 0 ? 'stock_in' : 'stock_out',
                    'quantity' => abs($delta),
                    'quantity_before' => $old['quantity'],
                    'quantity_after' => $newQuantity,
                    'reference' => 'ADJ-' . Str::upper(Str::random(12)),
                    'user_id' => Auth::id(),
                    'occurred_at' => now(),
                    'source_type' => 'inventory_adjustment',
                    'notes' => 'Quantity adjusted during item update.',
                ]);
            }
        });

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

    public function stockIn(Request $request, InventoryItem $inventoryItem)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $inventoryItem) {
            $item = InventoryItem::whereKey($inventoryItem->id)->lockForUpdate()->firstOrFail();
            $before = $item->quantity;
            $item->increment('quantity', (int) $request->quantity);
            $item->refresh()->syncStatus();

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'type' => 'stock_in',
                'quantity' => $request->quantity,
                'quantity_before' => $before,
                'quantity_after' => $item->quantity,
                'reference' => 'IN-' . Str::upper(Str::random(12)),
                'user_id' => Auth::id(),
                'occurred_at' => now(),
                'source_type' => 'manual_stock_in',
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('inventory.edit', $inventoryItem)
            ->with('success', 'Stock-in recorded successfully.');
    }

    public function toggleActive(InventoryItem $inventoryItem)
    {
        $inventoryItem->update(['is_active' => !$inventoryItem->is_active]);

        AuditService::log(
            $inventoryItem->is_active ? 'activated' : 'deactivated',
            'inventory',
            $inventoryItem->name,
            $inventoryItem->id,
            null,
            ['is_active' => $inventoryItem->is_active]
        );

        return redirect()->route('inventory.index')
            ->with('success', $inventoryItem->is_active ? 'Item activated.' : 'Item deactivated.');
    }
}

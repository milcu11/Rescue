<?php

namespace App\Http\Controllers;

use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReliefController extends Controller
{
    public function index()
    {
        $operations = ReliefOperation::with(['creator', 'distributions'])
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total'     => $operations->count(),
            'planned'   => $operations->where('status', 'planned')->count(),
            'active'    => $operations->where('status', 'active')->count(),
            'completed' => $operations->where('status', 'completed')->count(),
        ];

        return view('relief.index', compact('operations', 'summary'));
    }

    public function create()
    {
        return view('relief.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:planned,active,completed,cancelled',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'incident_name' => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $operation = ReliefOperation::create([
            ...$request->only([
                'name', 'description', 'status',
                'start_date', 'end_date',
                'incident_name', 'notes',
            ]),
            'created_by' => Auth::id(),
        ]);

        AuditService::created(
            'relief_operations',
            $operation->name,
            $operation->id,
            $operation->toArray()
        );

        return redirect()->route('relief.index')
            ->with('success', 'Relief operation created successfully.');
    }

    public function show(ReliefOperation $relief)
    {
        $relief->load([
            'distributions.center',
            'distributions.item',
            'distributions.distributor',
        ]);

        $centers = EvacuationCenter::where('status', '!=', 'closed')->get();
        $items   = InventoryItem::where('quantity', '>', 0)->get();

        return view('relief.show', compact('relief', 'centers', 'items'));
    }

    public function edit(ReliefOperation $relief)
    {
        return view('relief.edit', compact('relief'));
    }

    public function update(Request $request, ReliefOperation $relief)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:planned,active,completed,cancelled',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'incident_name' => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $old = $relief->toArray();

        $relief->update($request->only([
            'name', 'description', 'status',
            'start_date', 'end_date',
            'incident_name', 'notes',
        ]));

        AuditService::updated(
            'relief_operations',
            $relief->name,
            $relief->id,
            $old,
            $relief->fresh()->toArray()
        );

        return redirect()->route('relief.index')
            ->with('success', 'Operation updated successfully.');
    }

    public function destroy(ReliefOperation $relief)
    {
        AuditService::deleted('relief_operations', $relief->name, $relief->id);
        $relief->delete();

        return redirect()->route('relief.index')
            ->with('success', 'Operation removed.');
    }

    // Record a distribution under an operation
    public function distribute(Request $request, ReliefOperation $relief)
    {
        $validator = Validator::make($request->all(), [
            'evacuation_center_id'  => 'required|exists:evacuation_centers,id',
            'inventory_item_id'     => 'required|exists:inventory_items,id',
            'quantity_distributed'  => 'required|integer|min:1',
            'beneficiaries_count'   => 'required|integer|min:0',
            'notes'                 => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item = InventoryItem::findOrFail($request->inventory_item_id);

        // Check if enough stock
        if ($item->quantity < $request->quantity_distributed) {
            return redirect()->back()
                ->withErrors(['quantity_distributed' =>
                    "Not enough stock. Available: {$item->quantity} {$item->unit}."])
                ->withInput();
        }

        $center = EvacuationCenter::findOrFail($request->evacuation_center_id);

        DB::transaction(function () use ($request, $relief, $item) {
            // Record distribution
            ReliefDistribution::create([
                ...$request->only([
                    'evacuation_center_id',
                    'inventory_item_id',
                    'quantity_distributed',
                    'beneficiaries_count',
                    'notes',
                ]),
                'relief_operation_id' => $relief->id,
                'distributed_at'      => now(),
                'distributed_by'      => Auth::id(),
            ]);

            // Deduct from inventory
            $item->decrement('quantity', $request->quantity_distributed);
        });

        AuditService::log(
            'created',
            'relief_operations',
            "Distribution: {$item->name} to {$center->name} under {$relief->name}",
            $relief->id,
            null,
            [
                'item'         => $item->name,
                'quantity'     => $request->quantity_distributed,
                'center'       => $center->name,
                'beneficiaries'=> $request->beneficiaries_count,
            ]
        );

        NotificationService::distributionRecorded(
            $relief->name,
            $center->name,
            route('relief.show', $relief)
        );

        return redirect()->route('relief.show', $relief)
            ->with('success', 'Distribution recorded and inventory updated.');
    }
}

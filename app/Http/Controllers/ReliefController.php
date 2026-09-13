<?php

namespace App\Http\Controllers;

use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        $items   = InventoryItem::where('is_active', true)->where('quantity', '>', 0)->get();

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

    public function approve(ReliefOperation $relief)
    {
        $old = $relief->toArray();
        $relief->update([
            'approval_status' => 'approved',
            'status' => $relief->status === 'planned' ? 'active' : $relief->status,
        ]);

        AuditService::updated(
            'relief_operations',
            $relief->name,
            $relief->id,
            $old,
            $relief->fresh()->toArray()
        );

        return redirect()->route('relief.show', $relief)
            ->with('success', 'Relief operation approved for distribution.');
    }

    public function reject(ReliefOperation $relief)
    {
        $old = $relief->toArray();
        $relief->update(['approval_status' => 'rejected']);

        AuditService::updated(
            'relief_operations',
            $relief->name,
            $relief->id,
            $old,
            $relief->fresh()->toArray()
        );

        return redirect()->route('relief.show', $relief)
            ->with('success', 'Relief operation rejected.');
    }

    // Record a distribution under an operation
    public function distribute(Request $request, ReliefOperation $relief)
    {
        if ($relief->approval_status !== 'approved' || $relief->status !== 'active') {
            return redirect()->back()
                ->withErrors(['distribution' => 'Only approved and active relief operations can release items.'])
                ->withInput();
        }

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

        $duplicate = $relief->distributions()
            ->where('evacuation_center_id', $request->evacuation_center_id)
            ->where('inventory_item_id', $request->inventory_item_id)
            ->where('quantity_distributed', $request->quantity_distributed)
            ->where('beneficiaries_count', $request->beneficiaries_count)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($duplicate) {
            return redirect()->back()
                ->withErrors(['quantity_distributed' => 'A matching distribution was already recorded within the last 24 hours.'])
                ->withInput();
        }

        $center = EvacuationCenter::findOrFail($request->evacuation_center_id);

        [$item, $distribution] = DB::transaction(function () use ($request, $relief) {
            $item = InventoryItem::whereKey($request->inventory_item_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->quantity < $request->quantity_distributed) {
                throw ValidationException::withMessages([
                    'quantity_distributed' =>
                        "Not enough stock. Available: {$item->quantity} {$item->unit}.",
                ]);
            }

            $before = $item->quantity;

            // Record distribution
            $distribution = ReliefDistribution::create([
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
            $item->refresh()->syncStatus();

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'type' => 'stock_out',
                'quantity' => $request->quantity_distributed,
                'quantity_before' => $before,
                'quantity_after' => $item->quantity,
                'reference' => 'OUT-DIST-' . $distribution->id,
                'user_id' => Auth::id(),
                'occurred_at' => now(),
                'source_type' => ReliefDistribution::class,
                'source_id' => $distribution->id,
                'notes' => $request->notes,
            ]);

            return [$item, $distribution];
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

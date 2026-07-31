<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReliefOperation;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiReliefController extends Controller
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

        return response()->json([
            'success' => true,
            'data'    => $operations,
            'summary' => $summary,
        ]);
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
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $operation = ReliefOperation::create($request->only([
            'name', 'description', 'status',
            'start_date', 'end_date',
            'incident_name', 'notes',
        ]));

        AuditService::created(
            'relief_operations',
            $operation->name,
            $operation->id,
            $operation->toArray()
        );

        return response()->json([
            'success' => true,
            'data'    => $operation,
        ], 201);
    }

    public function show(int $id)
    {
        $operation = ReliefOperation::findOrFail($id);
        $operation->load(['distributions.center', 'distributions.item']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $operation->id,
                'name'           => $operation->name,
                'status'         => $operation->status,
                'incident_id'    => $operation->incident_id,
                'incident_name'  => $operation->incident_name,
                'start_date'     => $operation->start_date,
                'end_date'       => $operation->end_date,
                'beneficiaries'  => $operation->total_beneficiaries,
                'centers_served' => $operation->centers_served,
                'distributions'  => $operation->distributions->map(fn($d) => [
                    'id'                   => $d->id,
                    'center'               => $d->center->name,
                    'item'                 => $d->item->name,
                    'quantity_distributed' => $d->quantity_distributed,
                    'unit'                 => $d->item->unit,
                    'beneficiaries_count'  => $d->beneficiaries_count,
                    'distributed_at'       => $d->distributed_at,
                ]),
            ],
        ]);
    }

    public function report(int $id)
    {
        $operation = ReliefOperation::findOrFail($id);
        $operation->load(['distributions.center', 'distributions.item']);

        $byItem = $operation->distributions
            ->groupBy('inventory_item_id')
            ->map(fn($group) => [
                'item'      => $group->first()->item->name,
                'unit'      => $group->first()->item->unit,
                'total_qty' => $group->sum('quantity_distributed'),
            ])->values();

        return response()->json([
            'success'   => true,
            'operation' => [
                'id'     => $operation->id,
                'name'   => $operation->name,
                'status' => $operation->status,
            ],
            'summary' => [
                'total_distributions' => $operation->distributions->count(),
                'total_beneficiaries' => $operation->total_beneficiaries,
                'centers_served'      => $operation->centers_served,
            ],
            'items_distributed' => $byItem,
        ]);
    }
}

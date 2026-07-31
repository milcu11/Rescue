<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvacuationCenter;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiEvacuationController extends Controller
{
    public function index()
    {
        $centers = EvacuationCenter::withCount([
                'evacuees as active_count' => fn ($query) =>
                    $query->where('status', 'checked_in'),
            ])
            ->orderBy('status')
            ->get();

        $summary = [
            'total_centers'   => $centers->count(),
            'active'          => $centers->where('status', 'active')->count(),
            'full'            => $centers->where('status', 'full')->count(),
            'closed'          => $centers->where('status', 'closed')->count(),
            'total_evacuees'  => $centers->sum('current_occupancy'),
        ];

        return response()->json([
            'success' => true,
            'data'    => $centers,
            'summary' => $summary,
        ]);
    }

    public function show(int $id)
    {
        $center = EvacuationCenter::findOrFail($id);
        $center->load('activeEvacuees');

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                => $center->id,
                'name'              => $center->name,
                'barangay'          => $center->barangay,
                'capacity'          => $center->capacity,
                'current_occupancy' => $center->current_occupancy,
                'occupancy_percent' => $center->occupancy_percent,
                'status'            => $center->status,
                'evacuees_count'    => $center->activeEvacuees->count(),
            ],
        ]);
    }

    public function evacuees(Request $request, int $id)
    {
        $center = EvacuationCenter::findOrFail($id);

        $evacuees = $center->evacuees()
            ->when($request->filled('status'),
                fn($query) => $query->where('status', $request->status))
            ->orderByDesc('checked_in_at')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success'     => true,
            'center_id'   => $center->id,
            'center_name' => $center->name,
            'total'       => $evacuees->total(),
            'data'        => $evacuees->items(),
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $center = EvacuationCenter::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,full,closed',
            'notes'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $old = $center->toArray();
        $center->update(['status' => $request->status]);

        AuditService::updated(
            'evacuation',
            "API: {$center->name} status changed",
            $center->id,
            $old,
            $center->fresh()->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'Center status updated.',
            'id'      => $center->id,
            'status'  => $center->status,
        ]);
    }
}

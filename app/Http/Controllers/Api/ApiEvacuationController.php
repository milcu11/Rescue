<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvacuationCenter;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiEvacuationController extends Controller
{
    protected function formatCenter(EvacuationCenter $center): array
    {
        $barangayCoordinates = [
            'san jose' => [14.5220, 121.2584],
            'san juan' => [14.5240455, 121.2676781],
        ];

        $normalizedBarangay = strtolower(trim((string) $center->barangay));
        $fallbackCoordinates = $barangayCoordinates[$normalizedBarangay] ?? [14.5171, 121.2672];

        $latitude = is_numeric($center->latitude) ? (float) $center->latitude : $fallbackCoordinates[0];
        $longitude = is_numeric($center->longitude) ? (float) $center->longitude : $fallbackCoordinates[1];

        return [
            'id' => $center->id,
            'name' => $center->name,
            'barangay' => $center->barangay,
            'address' => $center->address,
            'capacity' => (int) $center->capacity,
            'current_occupancy' => (int) $center->current_occupancy,
            'status' => $center->status,
            'families_registered' => (int) ($center->families_registered ?? 0),
            'medical_needs_count' => (int) ($center->medical_needs_count ?? 0),
            'contact_person' => $center->contact_person,
            'contact_phone' => $center->contact_phone,
            'intake_procedures' => $center->intake_procedures,
            'required_items' => $center->required_items,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'updated_at' => $center->updated_at?->toDateTimeString(),
        ];
    }

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
            'active'          => $centers->where('status', 'open')->count(),
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

    public function publicIndex()
    {
        $centers = EvacuationCenter::orderBy('name')->get();

        return response()->json([
            'data' => $centers->map(fn($center) => $this->formatCenter($center)),
            'meta' => [
                'count' => $centers->count(),
                'limit' => 200,
                'read_only' => true,
            ],
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

    public function publicShow(int $id)
    {
        $center = EvacuationCenter::findOrFail($id);

        return response()->json([
            'data' => [$this->formatCenter($center)],
            'meta' => [
                'count' => 1,
                'limit' => 1,
                'read_only' => true,
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
            'status' => 'required|in:open,active,full,closed',
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

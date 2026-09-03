<?php

namespace App\Http\Controllers;

use App\Models\EvacuationCenter;
use App\Models\Evacuee;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EvacuationController extends Controller
{
    public function index()
    {
        $centers = EvacuationCenter::withCount([
                'evacuees as active_count' => fn($q) =>
                    $q->where('status', 'checked_in')
            ])
            ->orderBy('status')
            ->get();

        $summary = [
            'total'          => $centers->count(),
            'active'         => $centers->where('status', 'open')->count(),
            'full'           => $centers->where('status', 'full')->count(),
            'closed'         => $centers->where('status', 'closed')->count(),
            'total_evacuees' => $centers->sum('current_occupancy'),
            'total_families' => (int) $centers->sum('families_registered'),
            'total_medical'  => (int) $centers->sum('medical_needs_count'),
        ];

        return view('evacuation.index', compact('centers', 'summary'));
    }

    public function create()
    {
        return view('evacuation.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                => 'required|string|max:255',
            'barangay'            => 'required|string|max:255',
            'address'             => 'required|string|max:255',
            'capacity'            => 'required|integer|min:1',
            'current_occupancy'   => 'nullable|integer|min:0',
            'status'              => 'required|in:open,full,closed,active',
            'families_registered' => 'nullable|integer|min:0',
            'medical_needs_count' => 'nullable|integer|min:0',
            'contact_person'      => 'nullable|string|max:255',
            'contact_phone'       => 'nullable|string|max:50',
            'intake_procedures'   => 'nullable|string',
            'required_items'      => 'nullable|string',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'notes'               => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coordinates = $this->resolveCoordinates($request->latitude, $request->longitude, $request->barangay);

        $centerData = $request->only([
            'name', 'barangay', 'address', 'status',
            'contact_person', 'contact_phone',
            'intake_procedures', 'required_items',
            'notes',
        ]);
        $centerData['capacity'] = (int) $request->input('capacity', 1);
        $centerData['current_occupancy'] = (int) ($request->input('current_occupancy', 0) ?? 0);
        $centerData['families_registered'] = (int) ($request->input('families_registered', 0) ?? 0);
        $centerData['medical_needs_count'] = (int) ($request->input('medical_needs_count', 0) ?? 0);
        $centerData['latitude'] = $coordinates['latitude'];
        $centerData['longitude'] = $coordinates['longitude'];
        $centerData['created_by'] = Auth::id() ?? 1;

        $center = EvacuationCenter::create($centerData);

        AuditService::created(
            'evacuation',
            $center->name,
            $center->id,
            $center->toArray()
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Evacuation center registered successfully.',
                'data' => $center,
            ]);
        }

        return redirect()->route('evacuation.index')
            ->with('success', 'Evacuation center registered successfully.');
    }

    public function show(EvacuationCenter $evacuation)
    {
        $evacuees = $evacuation->evacuees()
            ->orderByDesc('checked_in_at')
            ->get();

        return view('evacuation.show', compact('evacuation', 'evacuees'));
    }

    public function evacuees(EvacuationCenter $evacuation)
    {
        $evacuees = $evacuation->evacuees()
            ->orderByDesc('checked_in_at')
            ->get()
            ->map(function ($evacuee) {
                return [
                    'id' => $evacuee->id,
                    'name' => $evacuee->name,
                    'family_group' => $evacuee->family_group,
                    'family_members' => $evacuee->family_members,
                    'barangay_origin' => $evacuee->barangay_origin,
                    'needs' => $evacuee->needs,
                    'id_presented' => $evacuee->id_presented,
                    'status' => $evacuee->status,
                    'checked_in_at' => optional($evacuee->checked_in_at)->toDateTimeString(),
                    'checked_out_at' => optional($evacuee->checked_out_at)->toDateTimeString(),
                ];
            });

        return response()->json([
            'data' => $evacuees,
            'total' => $evacuees->count(),
        ]);
    }

    public function edit(EvacuationCenter $evacuation)
    {
        return view('evacuation.edit', compact('evacuation'));
    }

    public function update(Request $request, EvacuationCenter $evacuation)
    {
        $validator = Validator::make($request->all(), [
            'name'                => 'required|string|max:255',
            'barangay'            => 'required|string|max:255',
            'address'             => 'required|string|max:255',
            'capacity'            => 'required|integer|min:1',
            'status'              => 'required|in:open,full,closed,active',
            'current_occupancy'   => 'nullable|integer|min:0',
            'families_registered' => 'nullable|integer|min:0',
            'medical_needs_count' => 'nullable|integer|min:0',
            'contact_person'      => 'nullable|string|max:255',
            'contact_phone'       => 'nullable|string|max:50',
            'intake_procedures'   => 'nullable|string',
            'required_items'      => 'nullable|string',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'notes'               => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coordinates = $this->resolveCoordinates($request->latitude, $request->longitude, $request->barangay);
        $old = $evacuation->toArray();

        $updateData = $request->only([
            'name', 'barangay', 'address', 'status',
            'contact_person', 'contact_phone',
            'intake_procedures', 'required_items',
            'notes',
        ]);
        $updateData['capacity'] = (int) $request->input('capacity', 1);
        $updateData['current_occupancy'] = (int) ($request->input('current_occupancy', 0) ?? 0);
        $updateData['families_registered'] = (int) ($request->input('families_registered', 0) ?? 0);
        $updateData['medical_needs_count'] = (int) ($request->input('medical_needs_count', 0) ?? 0);
        $updateData['latitude'] = $coordinates['latitude'];
        $updateData['longitude'] = $coordinates['longitude'];

        $evacuation->update($updateData);

        AuditService::updated(
            'evacuation',
            $evacuation->name,
            $evacuation->id,
            $old,
            $evacuation->fresh()->toArray()
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Center updated successfully.',
                'data' => $evacuation->fresh(),
            ]);
        }

        return redirect()->route('evacuation.index')
            ->with('success', 'Center updated successfully.');
    }

    private function resolveCoordinates($latitude, $longitude, ?string $barangay = null): array
    {
        if (is_numeric($latitude) && is_numeric($longitude)) {
            return [
                'latitude' => (float) $latitude,
                'longitude' => (float) $longitude,
            ];
        }

        $barangayCoordinates = [
            'san jose' => [14.5220, 121.2584],
            'san juan' => [14.5240455, 121.2676781],
        ];
        $normalizedBarangay = strtolower(trim((string) $barangay));
        $coordinates = $barangayCoordinates[$normalizedBarangay] ?? [14.5171, 121.2672];

        return [
            'latitude' => $coordinates[0],
            'longitude' => $coordinates[1],
        ];
    }

    public function destroy(EvacuationCenter $evacuation)
    {
        AuditService::deleted('evacuation', $evacuation->name, $evacuation->id);
        $evacuation->delete();

        return redirect()->route('evacuation.index')
            ->with('success', 'Center removed.');
    }

    // Check in an evacuee
    public function checkin(Request $request, EvacuationCenter $evacuation)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'family_group'    => 'nullable|string|max:255',
            'family_members'  => 'required|integer|min:1',
            'barangay_origin' => 'nullable|string|max:255',
            'needs'           => 'nullable|string',
            'id_presented'    => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $evacuee = Evacuee::create([
            ...$request->only([
                'name', 'family_group', 'family_members', 'barangay_origin',
                'needs', 'id_presented', 'notes',
            ]),
            'evacuation_center_id' => $evacuation->id,
            'checked_in_at'        => now(),
            'recorded_by'          => Auth::id(),
        ]);

        AuditService::log(
            'created',
            'evacuation',
            "Evacuee check-in: {$evacuee->name} at {$evacuation->name}",
            $evacuee->id,
            null,
            ['family_members' => $evacuee->family_members, 'center' => $evacuation->name]
        );

        // Update occupancy count
        $evacuation->increment('current_occupancy', $request->family_members);
        $evacuation->updateStatus();

        return redirect()->route('evacuation.show', $evacuation)
            ->with('success', 'Evacuee checked in successfully.');
    }

    // Check out an evacuee
    public function checkout(EvacuationCenter $evacuation, Evacuee $evacuee)
    {
        $evacuee->update([
            'status'         => 'checked_out',
            'checked_out_at' => now(),
        ]);

        AuditService::log(
            'updated',
            'evacuation',
            "Evacuee check-out: {$evacuee->name} from {$evacuation->name}",
            $evacuee->id,
            ['status' => 'checked_in'],
            ['status' => 'checked_out', 'checked_out_at' => now()]
        );

        // Reduce occupancy
        $newOccupancy = max(0, $evacuation->current_occupancy - $evacuee->family_members);
        $evacuation->update(['current_occupancy' => $newOccupancy]);
        $evacuation->updateStatus();

        return redirect()->route('evacuation.show', $evacuation)
            ->with('success', 'Evacuee checked out.');
    }
}

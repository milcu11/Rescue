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
            'total'       => $centers->count(),
            'active'      => $centers->where('status', 'active')->count(),
            'full'        => $centers->where('status', 'full')->count(),
            'closed'      => $centers->where('status', 'closed')->count(),
            'total_evacuees' => $centers->sum('current_occupancy'),
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
            'name'           => 'required|string|max:255',
            'barangay'       => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'capacity'       => 'required|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $center = EvacuationCenter::create([
            ...$request->only([
                'name', 'barangay', 'address', 'capacity',
                'contact_person', 'contact_number',
                'latitude', 'longitude', 'notes',
            ]),
            'created_by' => Auth::id(),
        ]);

        AuditService::created(
            'evacuation',
            $center->name,
            $center->id,
            $center->toArray()
        );

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

    public function edit(EvacuationCenter $evacuation)
    {
        return view('evacuation.edit', compact('evacuation'));
    }

    public function update(Request $request, EvacuationCenter $evacuation)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'barangay'       => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'capacity'       => 'required|integer|min:1',
            'status'         => 'required|in:active,full,closed',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $old = $evacuation->toArray();

        $evacuation->update($request->only([
            'name', 'barangay', 'address', 'capacity', 'status',
            'contact_person', 'contact_number',
            'latitude', 'longitude', 'notes',
        ]));

        AuditService::updated(
            'evacuation',
            $evacuation->name,
            $evacuation->id,
            $old,
            $evacuation->fresh()->toArray()
        );

        return redirect()->route('evacuation.index')
            ->with('success', 'Center updated successfully.');
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
                'name', 'family_members', 'barangay_origin',
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

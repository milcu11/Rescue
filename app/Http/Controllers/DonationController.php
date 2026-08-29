<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with('creator')
            ->when(Auth::user()?->role?->slug === 'lgu_staff', function ($query) {
                $query->where('type', 'in-kind');
            })
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total'       => $donations->count(),
            'pending'     => $donations->where('status', 'pending')->count(),
            'received'    => $donations->where('status', 'received')->count(),
            'distributed' => $donations->where('status', 'distributed')->count(),
        ];

        return view('donations.index', compact('donations', 'summary'));
    }

    public function create()
    {
        return view('donations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donor_name'        => 'required|string|max:255',
            'donor_contact'     => 'nullable|string|max:20',
            'donor_email'       => 'nullable|email|max:255',
            'type'              => 'required|in:in-kind,monetary',
            'amount'            => 'nullable|numeric|min:0|required_if:type,monetary',
            'items_description' => 'nullable|string|required_if:type,in-kind',
            'location'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $donation = Donation::create([
            ...$request->only([
                'donor_name', 'donor_contact', 'donor_email',
                'type', 'amount', 'items_description',
                'location', 'notes',
            ]),
            'created_by' => Auth::id() ?? \App\Models\User::query()->value('id') ?? 1,
        ]);

        AuditService::created(
            'donations',
            "Donation {$donation->tracking_code} from {$donation->donor_name}",
            $donation->id,
            $donation->toArray()
        );

        NotificationService::newDonation(
            $request->donor_name,
            $donation->tracking_code,
            route('donations.show', $donation)
        );

        return redirect()->route('donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    public function show($donation)
    {
        $donation = Donation::withTrashed()->findOrFail($donation);

        return view('donations.show', compact('donation'));
    }

    public function edit(Donation $donation)
    {
        return view('donations.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        $validator = Validator::make($request->all(), [
            'donor_name'        => 'required|string|max:255',
            'donor_contact'     => 'nullable|string|max:20',
            'donor_email'       => 'nullable|email|max:255',
            'type'              => 'required|in:in-kind,monetary',
            'amount'            => 'nullable|numeric|min:0|required_if:type,monetary',
            'items_description' => 'nullable|string|required_if:type,in-kind',
            'status'            => 'required|in:pending,received,distributed',
            'received_by'       => 'nullable|string|max:255',
            'received_at'       => 'nullable|date',
            'location'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $old = $donation->toArray();

        $donation->update($request->only([
            'donor_name', 'donor_contact', 'donor_email',
            'type', 'amount', 'items_description',
            'status', 'received_by', 'received_at',
            'location', 'notes',
        ]));

        AuditService::updated(
            'donations',
            "Donation {$donation->tracking_code}",
            $donation->id,
            $old,
            $donation->fresh()->toArray()
        );

        return redirect()->route('donations.index')
            ->with('success', 'Donation updated successfully.');
    }

    public function destroy(Donation $donation)
    {
        AuditService::deleted(
            'donations',
            "Donation {$donation->tracking_code} from {$donation->donor_name}",
            $donation->id
        );

        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donation record removed.');
    }

    // Public tracking — no auth needed
    public function track(Request $request)
    {
        $code     = $request->query('code');
        $donation = null;
        $error    = null;

        if ($code) {
            $donation = Donation::where('tracking_code', $code)->first();
            if (!$donation) {
                $error = 'No donation found with that tracking code.';
            }
        }

        return view('donations.track', compact('donation', 'error', 'code'));
    }
}

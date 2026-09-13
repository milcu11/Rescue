<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with('creator')
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total'       => $donations->count(),
            'pending'     => $donations->where('status', 'pending')->count(),
            'received'    => $donations->where('status', 'received')->count(),
            'verified'    => $donations->where('status', 'verified')->count(),
            'allocated'   => $donations->where('status', 'allocated')->count(),
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

        $duplicate = Donation::where('created_at', '>=', now()->subDay())
            ->where('type', $request->type)
            ->where(function ($query) use ($request) {
                if ($request->filled('donor_email')) {
                    $query->where('donor_email', $request->donor_email);
                } else {
                    $query->where('donor_name', $request->donor_name);
                }
            })
            ->when($request->type === 'monetary', fn ($query) => $query->where('amount', $request->amount))
            ->when($request->type === 'in-kind', fn ($query) => $query->where('items_description', $request->items_description))
            ->exists();

        if ($duplicate) {
            return redirect()->back()
                ->withErrors(['donor_name' => 'A matching donation was already recorded within the last 24 hours.'])
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
        $inventoryItems = InventoryItem::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('donations.edit', compact('donation', 'inventoryItems'));
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
            'status'            => 'required|in:pending,received,verified,allocated,distributed',
            'inventory_item_id' => 'nullable|exists:inventory_items,id|required_if:status,verified',
            'inventory_quantity'=> 'nullable|integer|min:1|required_if:status,verified',
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

        if (!$donation->canTransitionTo($request->status)) {
            return redirect()->back()
                ->withErrors(['status' => "Donation status cannot move from {$donation->status} to {$request->status}."])
                ->withInput();
        }

        if ($request->status === 'verified' && $donation->type !== 'in-kind') {
            return redirect()->back()
                ->withErrors(['status' => 'Only in-kind donations require inventory verification.'])
                ->withInput();
        }

        $old = $donation->toArray();

        DB::transaction(function () use ($request, $donation) {
            if ($request->status === 'verified' && !$donation->inventory_linked_at) {
                $item = InventoryItem::whereKey($request->inventory_item_id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$item) {
                    abort(422, 'The selected inventory item is inactive or unavailable.');
                }

                $before = $item->quantity;
                $item->increment('quantity', (int) $request->inventory_quantity);
                $item->refresh()->syncStatus();

                InventoryMovement::create([
                    'inventory_item_id' => $item->id,
                    'type' => 'stock_in',
                    'quantity' => $request->inventory_quantity,
                    'quantity_before' => $before,
                    'quantity_after' => $item->quantity,
                    'reference' => 'IN-DON-' . $donation->tracking_code,
                    'user_id' => Auth::id(),
                    'occurred_at' => now(),
                    'source_type' => Donation::class,
                    'source_id' => $donation->id,
                    'notes' => "Verified in-kind donation {$donation->tracking_code}.",
                ]);

                $donation->inventory_item_id = $item->id;
                $donation->inventory_quantity = $request->inventory_quantity;
                $donation->inventory_linked_at = now();
            }

            $donation->fill($request->only([
                'donor_name', 'donor_contact', 'donor_email',
                'type', 'amount', 'items_description',
                'status', 'received_by', 'received_at',
                'location', 'notes',
            ]));
            $donation->save();
        });

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

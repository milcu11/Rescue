<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiDonationController extends Controller
{
    protected function formatDonation(Donation $donation): array
    {
        return [
            'id'               => $donation->id,
            'tracking_code'    => $donation->tracking_code,
            'donor_name'       => $donation->donor_name,
            'type'             => $donation->type,
            'amount'           => $donation->isMonetary() ? $donation->amount : null,
            'items_description'=> $donation->type === 'in-kind' ? $donation->items_description : null,
            'status'           => $donation->status,
            'created_at'       => $donation->created_at?->toDateTimeString(),
            'updated_at'       => $donation->updated_at?->toDateTimeString(),
        ];
    }

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

        return response()->json([
            'success' => true,
            'data'    => $donations,
            'summary' => $summary,
        ]);
    }

    public function publicIndex()
    {
        $donations = Donation::orderByDesc('created_at')->get();

        return response()->json([
            'data' => $donations->map(fn($donation) => $this->formatDonation($donation)),
            'meta' => [
                'count' => $donations->count(),
                'limit' => 200,
                'read_only' => true,
            ],
        ]);
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
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
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
            return response()->json([
                'success' => false,
                'message' => 'A matching donation was already recorded within the last 24 hours.',
            ], 409);
        }

        $donation = Donation::create(array_merge($request->only([
            'donor_name', 'donor_contact', 'donor_email',
            'type', 'amount', 'items_description',
            'location', 'notes',
        ]), [
            'created_by' => Auth::guard('api')->id() ?? \App\Models\User::query()->value('id') ?? 1,
        ]));

        AuditService::created(
            'donations',
            "Donation {$donation->tracking_code} from {$donation->donor_name}",
            $donation->id,
            $donation->toArray()
        );

        NotificationService::newDonation(
            $donation->donor_name,
            $donation->tracking_code,
            null
        );

        return response()->json([
            'success' => true,
            'data'    => $donation,
        ], 201);
    }

    public function show(int $id)
    {
        $donation = Donation::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $donation,
        ]);
    }

    public function publicShow(int $id)
    {
        $donation = Donation::findOrFail($id);

        return response()->json([
            'data' => [$this->formatDonation($donation)],
            'meta' => [
                'count' => 1,
                'limit' => 1,
                'read_only' => true,
            ],
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $donation = Donation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status'      => 'required|in:pending,received,verified,allocated,distributed',
            'received_by' => 'nullable|string|max:255',
            'received_at' => 'nullable|date',
            'notes'       => 'nullable|string',
            'inventory_item_id' => 'nullable|exists:inventory_items,id|required_if:status,verified',
            'inventory_quantity' => 'nullable|integer|min:1|required_if:status,verified',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (!$donation->canTransitionTo($request->status)) {
            return response()->json([
                'success' => false,
                'message' => "Donation status cannot move from {$donation->status} to {$request->status}.",
            ], 422);
        }

        if ($request->status === 'verified' && $donation->type !== 'in-kind') {
            return response()->json([
                'success' => false,
                'message' => 'Only in-kind donations require inventory verification.',
            ], 422);
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
                    'user_id' => Auth::guard('api')->id(),
                    'occurred_at' => now(),
                    'source_type' => Donation::class,
                    'source_id' => $donation->id,
                    'notes' => "Verified in-kind donation {$donation->tracking_code}.",
                ]);

                $donation->inventory_item_id = $item->id;
                $donation->inventory_quantity = $request->inventory_quantity;
                $donation->inventory_linked_at = now();
            }

            $donation->update($request->only([
                'status', 'received_by', 'received_at', 'notes',
            ]));
        });

        AuditService::updated(
            'donations',
            "Donation {$donation->tracking_code}",
            $donation->id,
            $old,
            $donation->fresh()->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'Donation status updated.',
            'id'      => $donation->id,
            'status'  => $donation->status,
        ]);
    }

    public function track(string $code)
    {
        $donation = Donation::with('creator')
            ->where('tracking_code', $code)
            ->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found for the provided tracking code.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatDonation($donation),
        ]);
    }
}

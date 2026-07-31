<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiDonationController extends Controller
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
            'distributed' => $donations->where('status', 'distributed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data'    => $donations,
            'summary' => $summary,
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

        $donation = Donation::create($request->only([
            'donor_name', 'donor_contact', 'donor_email',
            'type', 'amount', 'items_description',
            'location', 'notes',
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

    public function updateStatus(Request $request, int $id)
    {
        $donation = Donation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status'      => 'required|in:pending,received,distributed',
            'received_by' => 'nullable|string|max:255',
            'received_at' => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $old = $donation->toArray();
        $donation->update($request->only([
            'status', 'received_by', 'received_at', 'notes',
        ]));

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
            'data'    => $donation,
        ]);
    }
}

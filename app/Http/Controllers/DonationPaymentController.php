<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationPayment;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DonationPaymentController extends Controller
{
    public function create(Donation $donation)
    {
        if ($donation->type !== 'monetary') {
            return redirect()->route('donations.show', $donation)
                ->with('error', 'Payment is only available for monetary donations.');
        }

        if ($donation->payment_status === 'paid') {
            return redirect()->route('donations.show', $donation)
                ->with('error', 'This donation has already been paid.');
        }

        $payment = $donation->payments()->whereIn('status', ['pending', 'pending_verification', 'rejected'])
            ->latest()
            ->first();

        $view = Auth::check() ? 'donations.payment.create' : 'public.gcash-pay';

        return view($view, compact('donation', 'payment'));
    }

    public function checkout(Request $request, Donation $donation)
    {
        if ($donation->payment_status === 'paid') {
            return redirect()->route('donations.show', $donation)
                ->with('error', 'This donation has already been paid.');
        }

        $data = $request->validate([
            'gcash_reference_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('donation_payments', 'gcash_reference_number'),
            ],
            'proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $proofPath = $request->hasFile('proof_image')
            ? $request->file('proof_image')->store('gcash-proofs', 'public')
            : null;

        $payment = $donation->payments()->whereIn('status', ['pending', 'rejected'])->latest()->first();

        if ($payment) {
            $payment->update([
                'payment_method' => 'gcash',
                'gcash_reference_number' => $data['gcash_reference_number'],
                'proof_image_path' => $proofPath ?? $payment->proof_image_path,
                'status' => 'pending_verification',
                'rejection_reason' => null,
            ]);
        } else {
            $payment = DonationPayment::create([
                'donation_id' => $donation->id,
                'payment_method' => 'gcash',
                'gcash_reference_number' => $data['gcash_reference_number'],
                'proof_image_path' => $proofPath,
                'amount' => $donation->amount,
                'status' => 'pending_verification',
            ]);
        }

        $donation->update(['payment_status' => 'verifying']);

        AuditService::log(
            'created',
            'donations',
            "GCash reference submitted for {$donation->tracking_code}",
            $donation->id,
            null,
            ['gcash_reference_number' => $data['gcash_reference_number']]
        );

        NotificationService::sendToRole(
            'mdrrmo',
            'new_donation',
            'GCash payment awaiting verification',
            "Donation {$donation->tracking_code} — ₱" . number_format($donation->amount, 2) . ' needs reference verification.',
            route('donations.payment.verifications')
        );

        return redirect()->route('donations.payment.success', $donation);
    }

    public function success(Donation $donation)
    {
        $payment = $donation->payments()->latest()->first();

        $view = Auth::check() ? 'donations.payment.submitted' : 'public.gcash-pay-submitted';

        return view($view, compact('donation', 'payment'));
    }

    public function history()
    {
        $payments = DonationPayment::with('donation')
            ->orderByDesc('created_at')
            ->paginate(30);

        $summary = [
            'total' => DonationPayment::count(),
            'pending' => DonationPayment::whereIn('status', ['pending', 'pending_verification'])->count(),
            'paid' => DonationPayment::where('status', 'paid')->count(),
            'failed' => DonationPayment::whereIn('status', ['failed', 'rejected'])->count(),
            'total_amount' => DonationPayment::where('status', 'paid')->sum('amount'),
        ];

        return view('donations.payment.history', compact('payments', 'summary'));
    }

    public function verifications()
    {
        $payments = DonationPayment::with('donation')
            ->where('status', 'pending_verification')
            ->orderBy('created_at')
            ->paginate(20);

        return view('donations.payment.verifications', compact('payments'));
    }

    public function confirm(DonationPayment $payment)
    {
        if ($payment->status !== 'pending_verification') {
            return redirect()->back()->with('error', 'This payment is not awaiting verification.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $donation = $payment->donation;
        $donation->update([
            'payment_status' => 'paid',
            'status' => 'received',
            'received_at' => now(),
        ]);

        AuditService::log(
            'updated',
            'donations',
            "GCash payment verified for {$donation->tracking_code}",
            $donation->id,
            ['payment_status' => 'verifying'],
            ['payment_status' => 'paid', 'gcash_reference_number' => $payment->gcash_reference_number]
        );

        return redirect()->route('donations.payment.verifications')
            ->with('success', "Payment for {$donation->tracking_code} confirmed.");
    }

    public function reject(Request $request, DonationPayment $payment)
    {
        if ($payment->status !== 'pending_verification') {
            return redirect()->back()->with('error', 'This payment is not awaiting verification.');
        }

        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'] ?? 'Reference number could not be verified.',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $payment->donation->update(['payment_status' => 'unpaid']);

        AuditService::log(
            'updated',
            'donations',
            "GCash payment rejected for {$payment->donation->tracking_code}",
            $payment->donation->id,
            ['payment_status' => 'verifying'],
            ['payment_status' => 'unpaid', 'rejection_reason' => $payment->rejection_reason]
        );

        return redirect()->route('donations.payment.verifications')
            ->with('success', "Payment for {$payment->donation->tracking_code} rejected. The donor can resubmit a reference.");
    }
}


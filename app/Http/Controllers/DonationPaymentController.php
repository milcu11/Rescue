<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationPayment;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Kirame\PayMongo\PayMongo;

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

        return view('donations.payment.create', compact('donation'));
    }

    public function checkout(Request $request, Donation $donation, PayMongo $paymongo)
    {
        $request->validate([
            'payment_method' => 'required|in:gcash,paymaya,card,grab_pay',
        ]);

        try {
            $amountInCentavos = (int) ($donation->amount * 100);

            $session = $paymongo->createCheckoutSession([
                'billing' => [
                    'name' => $donation->donor_name,
                    'email' => $donation->donor_email ?? 'donor@rescueph.ph',
                    'phone' => $donation->donor_contact ?? '',
                ],
                'line_items' => [[
                    'name' => "Donation — {$donation->tracking_code}",
                    'description' => 'Monetary donation to RescuePH disaster relief fund.',
                    'amount' => $amountInCentavos,
                    'currency' => 'PHP',
                    'quantity' => 1,
                ]],
                'payment_method_types' => [$request->payment_method],
                'success_url' => route('donations.payment.success', ['donation' => $donation->id, 'checkout_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('donations.payment.cancel', ['donation' => $donation->id]),
                'description' => "RescuePH donation {$donation->tracking_code}",
                'send_email_receipt' => true,
                'show_description' => true,
                'show_line_items' => true,
            ]);

            $checkoutId = $session['id'] ?? null;
            $checkoutUrl = $session['attributes']['checkout_url'] ?? null;

            if (!$checkoutId || !$checkoutUrl) {
                throw new \RuntimeException('PayMongo did not return a valid checkout session.');
            }

            $payment = DonationPayment::create([
                'donation_id' => $donation->id,
                'paymongo_checkout_id' => $checkoutId,
                'payment_method' => $request->payment_method,
                'amount' => $donation->amount,
                'status' => 'pending',
                'checkout_url' => $checkoutUrl,
                'paymongo_response' => $session,
            ]);

            $donation->update([
                'paymongo_checkout_id' => $checkoutId,
                'payment_status' => 'unpaid',
            ]);

            AuditService::log(
                'created',
                'donations',
                "Payment checkout created for {$donation->tracking_code}",
                $donation->id,
                null,
                ['method' => $request->payment_method, 'amount' => $donation->amount]
            );

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create payment session: ' . $e->getMessage());
        }
    }

    public function success(Request $request, Donation $donation, PayMongo $paymongo)
    {
        $checkoutId = $request->query('checkout_id') ?? $donation->paymongo_checkout_id;

        try {
            $session = $paymongo->retrieveCheckoutSession($checkoutId);
            $status = $session['attributes']['payment_status'] ?? 'pending';

            $payment = DonationPayment::where('donation_id', $donation->id)
                ->where('paymongo_checkout_id', $checkoutId)
                ->first();

            if ($status === 'paid') {
                $paymentId = $session['attributes']['payments'][0]['id'] ?? null;

                $payment?->update([
                    'status' => 'paid',
                    'paymongo_payment_id' => $paymentId,
                    'paid_at' => now(),
                    'paymongo_response' => $session,
                ]);

                $donation->update([
                    'payment_status' => 'paid',
                    'paymongo_payment_id' => $paymentId,
                    'status' => 'received',
                    'received_at' => now(),
                ]);

                AuditService::log(
                    'updated',
                    'donations',
                    "Payment confirmed for {$donation->tracking_code}",
                    $donation->id,
                    ['payment_status' => 'unpaid'],
                    ['payment_status' => 'paid', 'paymongo_payment_id' => $paymentId]
                );

                NotificationService::sendToRole(
                    'mdrrmo',
                    'new_donation',
                    'Donation Payment Confirmed',
                    "Online payment confirmed for donation {$donation->tracking_code} — ₱" . number_format($donation->amount, 2),
                    route('donations.show', $donation)
                );

                return view('donations.payment.success', compact('donation', 'payment'));
            }

            return redirect()->route('donations.show', $donation)
                ->with('error', 'Payment is still being processed. Please wait.');
        } catch (\Exception $e) {
            return redirect()->route('donations.show', $donation)
                ->with('error', 'Could not verify payment: ' . $e->getMessage());
        }
    }

    public function cancel(Donation $donation)
    {
        $donation->payments()->where('status', 'pending')->update(['status' => 'failed']);

        return redirect()->route('donations.show', $donation)
            ->with('error', 'Payment was cancelled.');
    }

    public function history()
    {
        $payments = DonationPayment::with('donation')
            ->orderByDesc('created_at')
            ->paginate(30);

        $summary = [
            'total' => DonationPayment::count(),
            'pending' => DonationPayment::where('status', 'pending')->count(),
            'paid' => DonationPayment::where('status', 'paid')->count(),
            'failed' => DonationPayment::where('status', 'failed')->count(),
            'total_amount' => DonationPayment::where('status', 'paid')->sum('amount'),
        ];

        return view('donations.payment.history', compact('payments', 'summary'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Paymongo-Signature');
        $secret = config('paymongo.webhook_secret');

        if ($secret && !hash_equals(hash_hmac('sha256', $payload, $secret), $sigHeader ?? '')) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);
        $type = $event['data']['attributes']['type'] ?? null;

        if ($type === 'payment.paid') {
            $paymentData = $event['data']['attributes']['data'] ?? [];
            $checkoutId = $paymentData['attributes']['checkout_session_id'] ?? null;
            $paymongoPayId = $paymentData['id'] ?? null;

            if ($checkoutId) {
                $payment = DonationPayment::where('paymongo_checkout_id', $checkoutId)->first();

                if ($payment && $payment->status !== 'paid') {
                    $payment->update([
                        'status' => 'paid',
                        'paymongo_payment_id' => $paymongoPayId,
                        'paid_at' => now(),
                    ]);

                    $payment->donation->update([
                        'payment_status' => 'paid',
                        'paymongo_payment_id' => $paymongoPayId,
                        'status' => 'received',
                        'received_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}

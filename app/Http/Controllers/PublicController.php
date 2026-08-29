<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationPayment;
use App\Models\EvacuationCenter;
use App\Models\User;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    public function home()
    {
        // Landing page removed — redirect to public evacuation centers list
        return redirect()->route('public.evac_centers');
    }

    public function evacCenters()
    {
        $centers = EvacuationCenter::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('public.evac_centers', compact('centers'));
    }

    public function evacCenterMapData()
    {
        $barangayCoordinates = [
            'san jose' => [14.5220, 121.2584],
            'san juan' => [14.5240455, 121.2676781],
        ];

        return EvacuationCenter::where('status', 'active')
            ->get(['id', 'name', 'barangay', 'latitude', 'longitude', 'capacity', 'current_occupancy'])
            ->map(function (EvacuationCenter $center) use ($barangayCoordinates) {
                if (is_numeric($center->latitude) && is_numeric($center->longitude)) {
                    return $center;
                }

                $normalizedBarangay = strtolower(trim((string) $center->barangay));
                $fallback = $barangayCoordinates[$normalizedBarangay] ?? [14.5171, 121.2672];
                $center->latitude = $fallback[0];
                $center->longitude = $fallback[1];

                return $center;
            });
    }

    public function evacCenter(EvacuationCenter $evacuationCenter)
    {
        return view('public.evac_center', ['center' => $evacuationCenter]);
    }

    public function donate()
    {
        return view('public.donate');
    }

    private function resolveCreatorId(): int
    {
        if (Auth::check()) {
            return Auth::id();
        }

        $user = User::query()->orderBy('id')->first();

        return $user?->id ?? 1;
    }

    public function paymentSuccess(Request $request, Donation $donation)
    {
        $requestedCheckoutId = $request->query('checkout_id');
        $checkoutId = $requestedCheckoutId && $requestedCheckoutId !== '{CHECKOUT_SESSION_ID}'
            ? $requestedCheckoutId
            : $donation->paymongo_checkout_id;

        try {
            $response = Http::withBasicAuth(config('paymongo.secret_key'), '')
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->connectTimeout(5)
                ->timeout((int) config('paymongo.timeout', 15))
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutId}");

            if (!$response->successful()) {
                return redirect()->route('donations.track', ['code' => $donation->tracking_code])
                    ->with('error', 'Could not verify payment.');
            }

            $session = $response->json('data');
            $attributes = $session['attributes'] ?? [];
            $paymentIntent = $attributes['payment_intent']['attributes'] ?? [];
            $status = $attributes['payment_status'] ?? $paymentIntent['status'] ?? $attributes['status'] ?? 'pending';
            $isPaid = in_array($status, ['paid', 'completed', 'succeeded'], true);

            $payment = DonationPayment::where('donation_id', $donation->id)
                ->where('paymongo_checkout_id', $checkoutId)
                ->first();

            if ($isPaid) {
                $paymentId = $attributes['payments'][0]['id']
                    ?? $paymentIntent['payments'][0]['id']
                    ?? $attributes['payment_intent']['id']
                    ?? null;

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
                    "Public payment confirmed for {$donation->tracking_code}",
                    $donation->id,
                    ['payment_status' => 'unpaid'],
                    ['payment_status' => 'paid']
                );

                NotificationService::sendToRole(
                    'mdrrmo',
                    'new_donation',
                    'Donation Payment Confirmed',
                    "Online payment confirmed for donation {$donation->tracking_code} — ₱" . number_format($donation->amount, 2),
                    route('donations.show', $donation)
                );

                return view('public.payment-success', compact('donation', 'payment'));
            }

            return redirect()->route('donations.track', ['code' => $donation->tracking_code])
                ->with('error', 'Payment is still being processed. Please wait.');
        } catch (\Throwable $e) {
            return redirect()->route('donations.track', ['code' => $donation->tracking_code])
                ->with('error', 'Verification error: ' . $e->getMessage());
        }
    }

    public function storeDonation(Request $request)
    {
        $donor = Auth::user()?->role?->slug === 'donor' ? Auth::user() : null;

        if ($donor) {
            $request->merge([
                'donor_name' => $donor->name,
                'donor_email' => $donor->email,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'donor_contact' => 'nullable|string|max:20',
            'donor_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:gcash,paymaya,card,grab_pay',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $donation = Donation::create([
            'donor_name' => $request->donor_name,
            'donor_contact' => $request->donor_contact,
            'donor_email' => $request->donor_email,
            'type' => 'monetary',
            'amount' => $request->amount,
            'notes' => $request->notes,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'created_by' => $this->resolveCreatorId(),
        ]);

        try {
            $amountInCentavos = (int) ($request->amount * 100);

            $response = Http::withBasicAuth(config('paymongo.secret_key'), '')
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->connectTimeout(5)
                ->timeout(min((int) config('paymongo.timeout', 15), 8))
                ->retry(1, 250)
                ->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'billing' => [
                                'name' => $request->donor_name,
                                'email' => $request->donor_email,
                                'phone' => $request->donor_contact ?? '',
                            ],
                            'line_items' => [[
                                'name' => "Donation — {$donation->tracking_code}",
                                'description' => 'Monetary donation to RescuePH disaster relief fund.',
                                'amount' => $amountInCentavos,
                                'currency' => 'PHP',
                                'quantity' => 1,
                            ]],
                            'payment_method_types' => [$request->payment_method],
                            'success_url' => route('public.payment.success', [
                                'donation' => $donation->id,
                                'checkout_id' => '{CHECKOUT_SESSION_ID}',
                            ]),
                            'cancel_url' => route('donate') . '?cancelled=1',
                            'description' => "RescuePH donation {$donation->tracking_code}",
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                        ],
                    ],
                ]);

            if (! $response->successful()) {
                $donation->forceDelete();
                $errorMessage = $response->json('errors.0.detail') ?? 'Payment session failed.';

                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
            }

            $session = $response->json('data');
            $checkoutId = $session['id'];
            $checkoutUrl = $session['attributes']['checkout_url'];

            DonationPayment::create([
                'donation_id' => $donation->id,
                'paymongo_checkout_id' => $checkoutId,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
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
                "Public donation {$donation->tracking_code} from {$donation->donor_name}",
                $donation->id,
                null,
                ['amount' => $request->amount, 'method' => $request->payment_method]
            );

            NotificationService::newDonation(
                $donation->donor_name,
                $donation->tracking_code,
                route('donations.show', $donation)
            );

            return redirect($checkoutUrl);
        } catch (\Throwable $e) {
            $donation->forceDelete();

            return redirect()->back()
            ->with('error', 'The payment gateway did not respond in time. Please try again.')
                ->withInput();
        }
    }
}

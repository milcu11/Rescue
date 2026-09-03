<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationPayment;
use App\Models\EvacuationCenter;
use App\Models\Evacuee;
use App\Models\User;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        // Landing page removed — redirect to public evacuation centers list
        return redirect()->route('public.evac_centers');
    }

    public function evacCenters()
    {
        $defaultCenterLat = 14.5171;
        $defaultCenterLng = 121.2672;

        $barangayCoordinates = [
            'san jose' => [14.5220, 121.2584],
            'san juan' => [14.5257, 121.2661],
            'concepcion' => [14.5152, 121.2690],
            'evangelista' => [14.5185, 121.2645],
            'mabini' => [14.5190, 121.2660],
            'rizal' => [14.5210, 121.2630],
            'san salvador' => [14.5240, 121.2610],
            'santiago' => [14.5200, 121.2680],
        ];

        $allCenters = EvacuationCenter::whereIn('status', ['open', 'full', 'active', 'closed'])
            ->orWhereNull('status')
            ->orderBy('name')
            ->get();

        $openCenters = $allCenters->filter(fn($c) => in_array($c->status, ['open', 'active', 'full']));

        // Process center objects with coordinates, distance, and slots
        $processedCenters = $openCenters->map(function ($c) use ($defaultCenterLat, $defaultCenterLng, $barangayCoordinates) {
            $lat = is_numeric($c->latitude) ? (float) $c->latitude : null;
            $lng = is_numeric($c->longitude) ? (float) $c->longitude : null;
            if (!$lat || !$lng) {
                $norm = strtolower(trim((string) $c->barangay));
                $fallback = $barangayCoordinates[$norm] ?? [$defaultCenterLat, $defaultCenterLng];
                $lat = $fallback[0];
                $lng = $fallback[1];
            }

            $distance = $this->calculateDistance($defaultCenterLat, $defaultCenterLng, $lat, $lng);
            $availableSlots = max(0, ((int) $c->capacity) - ((int) $c->current_occupancy));
            $isFull = $availableSlots <= 0 || $c->status === 'full';

            return [
                'id' => $c->id,
                'name' => $c->name,
                'address' => $c->address ?? 'Baras Rizal',
                'barangay_name' => $c->barangay ?? 'Baras',
                'latitude' => $lat,
                'longitude' => $lng,
                'capacity' => (int) $c->capacity,
                'occupancy' => (int) $c->current_occupancy,
                'available_slots' => $availableSlots,
                'families_count' => (int) ($c->families_registered ?? 0),
                'contact_phone' => $c->contact_phone ?? '',
                'contact_person' => $c->contact_person ?? '',
                'intake_procedures' => $c->intake_procedures ?? '',
                'required_items' => $c->required_items ?? '',
                'is_full' => $isFull,
                'is_nearest' => false,
                'distance_km' => $distance,
                'status' => $c->status === 'active' ? 'open' : $c->status,
                'marker' => [
                    'icon' => 'fa-home',
                    'marker_bg' => $isFull ? '#757575' : '#2e7d32',
                    'border_color' => $isFull ? '#424242' : '#1b5e20',
                ],
            ];
        })->values();

        // Find nearest center with available slots
        $nearestIndex = null;
        $minDistance = INF;
        foreach ($processedCenters as $idx => $c) {
            if (!$c['is_full'] && $c['distance_km'] < $minDistance) {
                $minDistance = $c['distance_km'];
                $nearestIndex = $idx;
            }
        }
        if ($nearestIndex === null && $processedCenters->isNotEmpty()) {
            $nearestIndex = 0;
        }

        if ($nearestIndex !== null && isset($processedCenters[$nearestIndex])) {
            $nearest = $processedCenters[$nearestIndex];
            $nearest['is_nearest'] = true;
            $nearest['marker']['marker_bg'] = '#1b5e20';
            $nearest['marker']['border_color'] = '#ffeb3b';
            $processedCenters[$nearestIndex] = $nearest;
        }

        $nearestCenter = $nearestIndex !== null ? $processedCenters[$nearestIndex] : null;

        $centerIntake = $processedCenters->map(function ($c) {
            return [
                'id' => $c['id'],
                'name' => $c['name'],
                'status' => $c['status'],
                'contact_person' => $c['contact_person'],
                'contact_phone' => $c['contact_phone'],
                'intake_procedures' => $c['intake_procedures'],
                'required_items' => $c['required_items'],
                'available_slots' => $c['available_slots'],
            ];
        })->values();

        $mapCfg = [
            'center' => ['lat' => 14.517099999999999, 'lng' => 121.2672],
            'zoom' => 12,
            'bounds' => [
                'sw' => ['lat' => 14.4956, 'lng' => 121.2433],
                'ne' => ['lat' => 14.6367, 'lng' => 121.3263]
            ],
            'maxBounds' => [
                'sw' => ['lat' => 14.4756, 'lng' => 121.2233],
                'ne' => ['lat' => 14.6567, 'lng' => 121.3463]
            ],
            'boundaryUrl' => 'https://drvms.freedev.app/assets/geo/baras-rizal.geojson',
            'label' => 'Municipality of Baras, Rizal',
            'municipality' => 'Municipality of Baras, Rizal',
        ];

        return view('public.evac_centers', [
            'centersData' => $processedCenters,
            'nearestCenter' => $nearestCenter,
            'centersJson' => $processedCenters,
            'centerIntakeJson' => $centerIntake,
            'mapCfg' => $mapCfg,
        ]);
    }

    public function captcha()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        session(['captcha_code' => $code]);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="44" viewBox="0 0 160 44">';
        $svg .= '<rect width="100%" height="100%" fill="#2b1b1d" rx="4"/>';
        for ($i = 0; $i < 4; $i++) {
            $x1 = rand(5, 155);
            $y1 = rand(5, 39);
            $x2 = rand(5, 155);
            $y2 = rand(5, 39);
            $color = ['#ef5350', '#ffb74d', '#42a5f5', '#ab47bc'][rand(0, 3)];
            $svg .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$color}\" stroke-width=\"1.5\" stroke-opacity=\"0.4\"/>";
        }
        for ($i = 0; $i < 5; $i++) {
            $char = $code[$i];
            $x = 18 + ($i * 26);
            $y = rand(28, 33);
            $rot = rand(-12, 12);
            $fill = ['#ffffff', '#ffcdd2', '#ffe082', '#b2ebf2', '#e1bee7'][$i % 5];
            $svg .= "<text x=\"{$x}\" y=\"{$y}\" fill=\"{$fill}\" font-family=\"'DM Sans', Arial, sans-serif\" font-size=\"22\" font-weight=\"bold\" transform=\"rotate({$rot} {$x} {$y})\">{$char}</text>";
        }
        $svg .= '</svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function nearestEvac(Request $request)
    {
        $lat = (float) $request->query('lat', 14.5171);
        $lng = (float) $request->query('lng', 121.2672);

        $centers = EvacuationCenter::whereIn('status', ['open', 'active'])
            ->get();

        $barangayCoordinates = [
            'san jose' => [14.5220, 121.2584],
            'san juan' => [14.5257, 121.2661],
            'concepcion' => [14.5152, 121.2690],
            'evangelista' => [14.5185, 121.2645],
            'mabini' => [14.5190, 121.2660],
            'rizal' => [14.5210, 121.2630],
            'san salvador' => [14.5240, 121.2610],
            'santiago' => [14.5200, 121.2680],
        ];

        $nearest = null;
        $minDist = INF;

        foreach ($centers as $c) {
            $cLat = is_numeric($c->latitude) ? (float) $c->latitude : null;
            $cLng = is_numeric($c->longitude) ? (float) $c->longitude : null;
            if (!$cLat || !$cLng) {
                $norm = strtolower(trim((string) $c->barangay));
                $fallback = $barangayCoordinates[$norm] ?? [14.5171, 121.2672];
                $cLat = $fallback[0];
                $cLng = $fallback[1];
            }

            $availableSlots = max(0, ((int) $c->capacity) - ((int) $c->current_occupancy));
            $dist = $this->calculateDistance($lat, $lng, $cLat, $cLng);

            if ($dist < $minDist) {
                $minDist = $dist;
                $nearest = [
                    'id' => $c->id,
                    'name' => $c->name,
                    'address' => $c->address ?? 'Baras Rizal',
                    'barangay_name' => $c->barangay ?? 'Baras',
                    'latitude' => $cLat,
                    'longitude' => $cLng,
                    'capacity' => (int) $c->capacity,
                    'occupancy' => (int) $c->current_occupancy,
                    'available_slots' => $availableSlots,
                    'distance_km' => $dist,
                    'contact_phone' => $c->contact_phone,
                    'contact_person' => $c->contact_person,
                ];
            }
        }

        return response()->json([
            'status' => 'ok',
            'nearest' => $nearest,
        ]);
    }

    public function registerFamily(Request $request)
    {
        // Honeypot check
        if ($request->filled('drms_website_hp')) {
            return redirect()->route('public.evac_centers');
        }

        // CAPTCHA check
        $sessionCaptcha = session('captcha_code');
        if (empty($sessionCaptcha) || strtoupper(trim((string) $request->captcha_code)) !== strtoupper(trim((string) $sessionCaptcha))) {
            return redirect()->back()
                ->with('error', 'Security verification (CAPTCHA) failed. Please try again.')
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'evacuation_center_id' => 'required|exists:evacuation_centers,id',
            'family_head_name'     => 'required|string|max:255',
            'members_count'        => 'required|integer|min:1|max:100',
            'medical_needs'        => 'nullable|string|max:255',
            'contact_phone'        => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $center = EvacuationCenter::findOrFail($request->evacuation_center_id);
        $token = 'FAM-' . strtoupper(Str::random(8));

        $evacuee = Evacuee::create([
            'evacuation_center_id' => $center->id,
            'family_qr_token'      => $token,
            'name'                 => $request->family_head_name,
            'family_group'         => $request->family_head_name . ' Family',
            'family_members'       => (int) $request->members_count,
            'barangay_origin'      => $center->barangay,
            'needs'                => $request->medical_needs,
            'contact_phone'        => $request->contact_phone,
            'id_presented'         => $token,
            'status'               => 'registered',
            'checked_in_at'        => null,
        ]);

        $center->increment('families_registered');
        if (!empty($request->medical_needs)) {
            $center->increment('medical_needs_count');
        }

        session()->forget('captcha_code');

        return redirect()->route('public.evac_centers')
            ->with('registered_family', [
                'name' => $evacuee->name,
                'token' => $token,
                'center' => $center->name,
                'members' => $evacuee->family_members,
            ])
            ->with('success', "Family pre-registered successfully! Your QR token is {$token}. Present this token at the registration desk upon arrival.");
    }

    public function checkInFamily(Request $request)
    {
        $token = trim((string) $request->family_qr_token);

        if (empty($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide a valid family QR token.',
                'data' => []
            ], 422);
        }

        $evacuee = Evacuee::with('center')
            ->where('family_qr_token', $token)
            ->orWhere('id_presented', $token)
            ->first();

        if (!$evacuee) {
            return response()->json([
                'status' => 'error',
                'message' => "Token '{$token}' not found. Please verify the QR token.",
                'data' => []
            ], 404);
        }

        $center = $evacuee->center;
        $alreadyCheckedIn = $evacuee->status === 'checked_in' && !empty($evacuee->checked_in_at);

        $responseData = [
            'family_head_name' => $evacuee->name,
            'family_qr_token'  => $evacuee->family_qr_token ?? $token,
            'members_count'    => $evacuee->family_members,
            'center_name'      => $center?->name ?? '—',
            'center_barangay'  => $center?->barangay ?? '—',
            'center_address'   => $center?->address ?? '—',
            'center_contact'   => $center?->contact_phone ?? '—',
            'medical_needs'    => $evacuee->needs ?: '—',
            'contact_phone'    => $evacuee->contact_phone ?: '—',
            'registered_at'    => $evacuee->created_at ? $evacuee->created_at->format('M d, Y h:i A') : '—',
            'checked_in_at'    => $evacuee->checked_in_at ? $evacuee->checked_in_at->format('M d, Y h:i A') : '—',
        ];

        if ($alreadyCheckedIn) {
            return response()->json([
                'status' => 'already',
                'message' => "Family {$evacuee->name} was already checked in on {$responseData['checked_in_at']}.",
                'data' => $responseData,
            ]);
        }

        $evacuee->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        if ($center) {
            $center->increment('current_occupancy', (int) ($evacuee->family_members ?: 1));
            $center->updateStatus();
        }

        $responseData['checked_in_at'] = now()->format('M d, Y h:i A');

        return response()->json([
            'status' => 'ok',
            'message' => "Family {$evacuee->name} successfully checked in at {$center?->name}.",
            'data' => $responseData,
        ]);
    }

    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
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

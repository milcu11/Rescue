<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Submitted | RescuePH</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'DM Sans', 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f9f5f4; }
    .card { border-radius: 16px; border: 0; box-shadow: 0 8px 30px rgba(0,0,0,.08); }
    .btn-donate { background: #3b0b0d; color: #fff; }
    .btn-donate:hover { background: #4b0f11; color: #fff; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-body p-4 p-md-5 text-center">
          <i class="fas fa-clock fa-3x text-warning mb-3"></i>
          <h2 class="mb-2">Thanks! Your payment is awaiting verification</h2>
          <p class="text-muted mb-4">
            We received your GCash reference number for donation <code>{{ $donation->tracking_code }}</code>.
            Our team will verify it against our GCash transaction history and confirm your donation shortly.
          </p>
          @if($payment?->gcash_reference_number)
            <p class="mb-4"><strong>Reference submitted:</strong> {{ $payment->gcash_reference_number }}</p>
          @endif
          <a href="{{ route('donations.track', ['code' => $donation->tracking_code]) }}" class="btn btn-donate mr-2 mb-2">
            <i class="fas fa-search mr-1"></i> Track this donation
          </a>
          <a href="{{ route('public.home') }}" class="btn btn-outline-secondary mb-2">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

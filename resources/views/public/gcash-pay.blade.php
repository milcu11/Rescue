<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay via GCash | RescuePH</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'DM Sans', 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f9f5f4; }
    .card { border-radius: 16px; border: 0; box-shadow: 0 8px 30px rgba(0,0,0,.08); }
    .btn-donate { background: #3b0b0d; color: #fff; }
    .btn-donate:hover { background: #4b0f11; color: #fff; }
    .gcash-qr-img { max-width: 260px; width: 100%; border: 1px solid #eee; border-radius: 12px; padding: 10px; background: #fff; }
    .gcash-ref-code { font-size: 1.1rem; letter-spacing: 1px; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="mb-2">Pay your donation via GCash</h2>
            <p class="text-muted mb-0">Donation <code>{{ $donation->tracking_code }}</code> — <strong>₱{{ number_format($donation->amount, 2) }}</strong></p>
          </div>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if($payment && $payment->status === 'rejected')
            <div class="alert alert-warning">
              Your previous reference number could not be verified{{ $payment->rejection_reason ? ': ' . $payment->rejection_reason : '.' }} Please double-check and submit again.
            </div>
          @endif

          <div class="row align-items-center mb-4">
            <div class="col-md-5 text-center mb-3 mb-md-0">
              <img src="{{ asset(config('gcash.qr_image')) }}" alt="GCash QR code" class="gcash-qr-img">
            </div>
            <div class="col-md-7">
              <p class="mb-2"><strong>Account name:</strong> {{ config('gcash.account_name') }}</p>
              @if(config('gcash.account_number'))
                <p class="mb-2"><strong>GCash number:</strong> {{ config('gcash.account_number') }}</p>
              @endif
              <p class="mb-2"><strong>Amount to send:</strong> ₱{{ number_format($donation->amount, 2) }}</p>
              <p class="mb-0 text-muted small">
                In the GCash app, scan the QR or send to the number above. Put <span class="gcash-ref-code font-weight-bold">{{ $donation->tracking_code }}</span> in the message/note field so we can match your payment.
              </p>
            </div>
          </div>

          <hr>

          <h5 class="mb-3">After paying, submit your GCash reference number</h5>
          <form action="{{ route('donations.payment.checkout', $donation) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label class="font-weight-semibold">GCash reference number <span class="text-danger">*</span></label>
              <input type="text" name="gcash_reference_number" class="form-control" value="{{ old('gcash_reference_number') }}" placeholder="e.g. 1234567890123" required>
              <small class="form-text text-muted">Shown on your GCash receipt/transaction history after sending.</small>
            </div>
            <div class="form-group">
              <label class="font-weight-semibold">Screenshot of receipt <small class="text-muted font-weight-normal">(optional, but recommended)</small></label>
              <input type="file" name="proof_image" class="form-control-file" accept="image/jpeg,image/png,image/webp">
            </div>

            <div class="d-flex flex-wrap align-items-center mt-3">
              <button type="submit" class="btn btn-donate mr-2 mb-2">
                <i class="fas fa-paper-plane mr-1"></i> Submit for verification
              </button>
              <a href="{{ route('donations.track', ['code' => $donation->tracking_code]) }}" class="btn btn-outline-secondary mb-2">Track donation instead</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

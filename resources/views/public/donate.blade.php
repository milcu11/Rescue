<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donate to RescuePH</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'Source Sans Pro', sans-serif; background: #f7f7f7; }
    .card { border-radius: 16px; border: 0; box-shadow: 0 8px 30px rgba(0,0,0,.08); }
    .btn-donate { background: #3b0b0d; color: #fff; }
    .btn-donate:hover { background: #4b0f11; color: #fff; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="mb-2">Support RescuePH</h2>
            <p class="text-muted">Your donation helps fund relief operations, food packs, and evacuation support.</p>
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

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form action="{{ route('donate.submit') }}" method="POST">
            @csrf
            @php $signedInDonor = auth()->user()?->role?->slug === 'donor' ? auth()->user() : null; @endphp
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                <input type="text" name="donor_name" class="form-control" value="{{ old('donor_name', $signedInDonor?->name) }}" {{ $signedInDonor ? 'readonly' : '' }} required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="donor_email" class="form-control" value="{{ old('donor_email', $signedInDonor?->email) }}" {{ $signedInDonor ? 'readonly' : '' }}>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Contact Number</label>
                <input type="text" name="donor_contact" class="form-control" value="{{ old('donor_contact') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                <select name="payment_method" class="form-control" required>
                  <option value="gcash" {{ old('payment_method', 'gcash') === 'gcash' ? 'selected' : '' }}>GCash</option>
                  <option value="paymaya" {{ old('payment_method') === 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                  <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                  <option value="grab_pay" {{ old('payment_method') === 'grab_pay' ? 'selected' : '' }}>GrabPay</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Amount (₱) <span class="text-danger">*</span></label>
              <input type="number" name="amount" class="form-control" min="100" step="0.01" value="{{ old('amount') }}" placeholder="100">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Notes</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Optional note for your donation">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex flex-wrap align-items-center">
              <button type="submit" class="btn btn-donate mr-2 mb-2">
                Donate Now
              </button>
              <a href="{{ url('/public-home') }}" class="btn btn-outline-secondary mb-2" onclick="if (window.history.length > 1) { window.history.back(); return false; }">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

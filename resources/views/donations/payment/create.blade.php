@extends('layouts.app')

@section('title', 'Pay Donation')
@section('page-title', 'Online Payment')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item"><a href="{{ route('donations.show', $donation) }}">{{ $donation->tracking_code }}</a></li>
  <li class="breadcrumb-item active">Pay</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Donation Summary</h3>
      </div>
      <div class="card-body">
        <table class="table table-borderless table-sm">
          <tr>
            <th class="text-muted">Tracking Code</th>
            <td><code>{{ $donation->tracking_code }}</code></td>
          </tr>
          <tr>
            <th class="text-muted">Donor</th>
            <td>{{ $donation->donor_name }}</td>
          </tr>
          <tr>
            <th class="text-muted">Amount</th>
            <td class="font-weight-bold" style="font-size:1.2rem;color:#3b0b0d;">
              ₱{{ number_format($donation->amount, 2) }}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header" style="background:#3b0b0d;">
        <h3 class="card-title text-white"><i class="fas fa-qrcode mr-2"></i>Pay via GCash</h3>
      </div>
      <div class="card-body">
        @if($payment && $payment->status === 'rejected')
          <div class="alert alert-warning">
            The previous reference number could not be verified{{ $payment->rejection_reason ? ': ' . $payment->rejection_reason : '.' }} Please double-check and submit again.
          </div>
        @endif

        <div class="row align-items-center mb-4">
          <div class="col-12 text-center mb-3">
            <img src="{{ asset(config('gcash.qr_image')) }}?v={{ filemtime(public_path(config('gcash.qr_image'))) }}" alt="GCash QR code" class="img-fluid" style="width:100%;max-width:560px;height:auto;border:1px solid #eee;border-radius:12px;padding:8px;background:#fff;">
          </div>
          <div class="col-12">
            <p class="mb-1"><strong>Account name:</strong> {{ config('gcash.account_name') }}</p>
            @if(config('gcash.account_number'))
              <p class="mb-1"><strong>GCash number:</strong> {{ config('gcash.account_number') }}</p>
            @endif
            <p class="mb-0 text-muted small">
              Put <strong>{{ $donation->tracking_code }}</strong> in the message/note field when sending so it can be matched.
            </p>
          </div>
        </div>

        <hr>

        <form action="{{ route('donations.payment.checkout', $donation) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label class="font-weight-bold">GCash reference number <span class="text-danger">*</span></label>
            <input type="text" name="gcash_reference_number" class="form-control" value="{{ old('gcash_reference_number') }}" placeholder="e.g. 1234567890123" required>
            @error('gcash_reference_number')
              <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Screenshot of receipt <small class="text-muted font-weight-normal">(optional)</small></label>
            <input type="file" name="proof_image" class="form-control-file" accept="image/jpeg,image/png,image/webp">
          </div>

          <div class="callout callout-info">
            <i class="fas fa-info-circle mr-1"></i>
            Submitting your reference number does not auto-confirm payment — MDRRMO staff will verify it against GCash transaction records before marking this donation as paid.
          </div>

          <button type="submit" class="btn btn-danger btn-block btn-lg">
            <i class="fas fa-paper-plane mr-2"></i>Submit for verification
          </button>

          <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

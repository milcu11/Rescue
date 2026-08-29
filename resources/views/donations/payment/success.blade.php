@extends('layouts.app')

@section('title', 'Payment Successful')
@section('page-title', 'Payment Confirmed')

@section('content')
<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card text-center">
      <div class="card-body py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h3 class="font-weight-bold">Payment Successful!</h3>
        <p class="text-muted mb-4">Your donation has been received and confirmed.</p>

        <table class="table table-borderless table-sm text-left">
          <tr>
            <th class="text-muted">Tracking Code</th>
            <td><code>{{ $donation->tracking_code }}</code></td>
          </tr>
          <tr>
            <th class="text-muted">Amount Paid</th>
            <td class="font-weight-bold text-success">₱{{ number_format($donation->amount, 2) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Payment Method</th>
            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
          </tr>
          <tr>
            <th class="text-muted">PayMongo ID</th>
            <td><small><code>{{ $payment->paymongo_payment_id }}</code></small></td>
          </tr>
          <tr>
            <th class="text-muted">Date</th>
            <td>{{ $payment->paid_at->format('M d, Y h:i A') }}</td>
          </tr>
        </table>

        <div class="callout callout-success mt-3">
          Thank you for your generous donation to RescuePH disaster relief operations.
        </div>

        <div class="mt-3 d-flex justify-content-center" style="gap:8px;">
          <a href="{{ route('donations.track') }}?code={{ $donation->tracking_code }}" class="btn btn-outline-danger">
            <i class="fas fa-search mr-1"></i>Track Donation
          </a>
          <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-home mr-1"></i>Go to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Payment Submitted')
@section('page-title', 'Payment Submitted')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item"><a href="{{ route('donations.show', $donation) }}">{{ $donation->tracking_code }}</a></li>
  <li class="breadcrumb-item active">Payment Submitted</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card text-center">
      <div class="card-body py-5">
        <i class="fas fa-clock fa-4x text-warning mb-3"></i>
        <h3 class="font-weight-bold">Awaiting verification</h3>
        <p class="text-muted mb-4">
          We received the GCash reference number for donation <code>{{ $donation->tracking_code }}</code>.
          MDRRMO staff will verify it against GCash transaction records shortly.
        </p>

        @if($payment?->gcash_reference_number)
          <table class="table table-borderless table-sm text-left">
            <tr>
              <th class="text-muted">Reference number</th>
              <td><code>{{ $payment->gcash_reference_number }}</code></td>
            </tr>
            <tr>
              <th class="text-muted">Amount</th>
              <td class="font-weight-bold">₱{{ number_format($donation->amount, 2) }}</td>
            </tr>
          </table>
        @endif

        <a href="{{ route('donations.show', $donation) }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left mr-1"></i>Back to Donation
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

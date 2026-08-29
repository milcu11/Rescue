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
  <div class="col-md-5">
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

  <div class="col-md-7">
    <div class="card">
      <div class="card-header" style="background:#3b0b0d;">
        <h3 class="card-title text-white"><i class="fas fa-credit-card mr-2"></i>Choose Payment Method</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('donations.payment.checkout', $donation) }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-6 mb-3">
              <label class="payment-card w-100" onclick="pick('gcash')">
                <input type="radio" name="payment_method" value="gcash" style="display:none;">
                <div class="payment-card-inner" id="card-gcash">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/GCash_logo.svg/512px-GCash_logo.svg.png" alt="GCash" style="height:36px;margin-bottom:6px;">
                  <div class="font-weight-bold">GCash</div>
                  <small class="text-muted">E-wallet payment</small>
                </div>
              </label>
            </div>

            <div class="col-6 mb-3">
              <label class="payment-card w-100" onclick="pick('paymaya')">
                <input type="radio" name="payment_method" value="paymaya" style="display:none;">
                <div class="payment-card-inner" id="card-paymaya">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Maya_%28payments%29_logo.svg/512px-Maya_%28payments%29_logo.svg.png" alt="Maya" style="height:36px;margin-bottom:6px;">
                  <div class="font-weight-bold">Maya</div>
                  <small class="text-muted">E-wallet payment</small>
                </div>
              </label>
            </div>

            <div class="col-6 mb-3">
              <label class="payment-card w-100" onclick="pick('grab_pay')">
                <input type="radio" name="payment_method" value="grab_pay" style="display:none;">
                <div class="payment-card-inner" id="card-grab_pay">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/GrabPay_Logo.svg/512px-GrabPay_Logo.svg.png" alt="GrabPay" style="height:36px;margin-bottom:6px;">
                  <div class="font-weight-bold">GrabPay</div>
                  <small class="text-muted">E-wallet payment</small>
                </div>
              </label>
            </div>

            <div class="col-6 mb-3">
              <label class="payment-card w-100" onclick="pick('card')">
                <input type="radio" name="payment_method" value="card" style="display:none;">
                <div class="payment-card-inner" id="card-card">
                  <i class="fas fa-credit-card fa-2x text-secondary d-block mb-1"></i>
                  <div class="font-weight-bold">Credit / Debit Card</div>
                  <small class="text-muted">Visa, Mastercard</small>
                </div>
              </label>
            </div>
          </div>

          @error('payment_method')
            <div class="text-danger small mb-3">Please select a payment method.</div>
          @enderror

          <div class="callout callout-info">
            <i class="fas fa-shield-alt mr-1"></i>
            Payments are securely processed by <strong>PayMongo</strong>. RescuePH does not store your card details.
          </div>

          <button type="submit" class="btn btn-danger btn-block btn-lg" id="pay-btn" disabled>
            <i class="fas fa-lock mr-2"></i>Proceed to Payment — ₱{{ number_format($donation->amount, 2) }}
          </button>

          <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .payment-card-inner {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 16px 10px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
  }
  .payment-card-inner:hover { border-color: #aaa; }
  .payment-card-inner.selected { border-color: #3b0b0d; background: #fff5f5; }
</style>
@endpush

@push('scripts')
<script>
  function pick(method) {
    document.querySelectorAll('.payment-card-inner').forEach(function (card) {
      card.classList.remove('selected');
    });
    document.getElementById('card-' + method).classList.add('selected');
    document.querySelector('input[value="' + method + '"]').checked = true;
    document.getElementById('pay-btn').disabled = false;
  }
</script>
@endpush

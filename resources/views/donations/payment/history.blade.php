@extends('layouts.app')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">Payment History</li>
@endsection

@section('content')
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-green text-white">
      <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total Transactions</p></div>
      <div class="icon"><i class="fas fa-credit-card"></i></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-yellow text-white">
      <div class="inner"><h3>{{ $summary['pending'] }}</h3><p>Pending</p></div>
      <div class="icon"><i class="fas fa-hourglass-half"></i></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-green text-white">
      <div class="inner"><h3>{{ $summary['paid'] }}</h3><p>Paid</p></div>
      <div class="icon"><i class="fas fa-check-circle"></i></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-brown text-white">
      <div class="inner"><h3>₱{{ number_format($summary['total_amount'], 0) }}</h3><p>Total Collected</p></div>
      <div class="icon"><i class="fas fa-coins"></i></div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title mb-0"><i class="fas fa-history mr-2"></i>All Payment Transactions</h3>
  </div>
  <div class="card-body p-0">
    <table id="paymentsTable" class="table table-bordered table-hover table-sm mb-0 align-middle">
      <thead class="thead-dark">
        <tr>
          <th>Tracking Code</th>
          <th>Donor</th>
          <th>Method</th>
          <th>Amount</th>
          <th>Status</th>
          <th>PayMongo ID</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $payment)
          <tr>
            <td><a href="{{ route('donations.show', $payment->donation) }}"><code>{{ $payment->donation->tracking_code }}</code></a></td>
            <td>{{ $payment->donation->donor_name }}</td>
            <td><span class="badge badge-info">{{ ucfirst(str_replace('_',' ',$payment->payment_method ?? '—')) }}</span></td>
            <td><strong>₱{{ number_format($payment->amount, 2) }}</strong></td>
            <td><span class="badge badge-{{ $payment->status_badge }}">{{ ucfirst($payment->status) }}</span></td>
            <td><small><code>{{ $payment->paymongo_payment_id ?? '—' }}</code></small></td>
            <td><small>{{ $payment->created_at->format('M d, Y h:i A') }}</small></td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="fas fa-inbox fa-2x d-block mb-2"></i>No payment transactions yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($payments->hasPages())
    <div class="card-footer clearfix">{{ $payments->links() }}</div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    $('#paymentsTable').DataTable({
      pageLength: 25,
      order: [[6, 'desc']],
      columnDefs: [{ orderable: false, targets: [-1] }]
    });
  });
</script>
@endpush

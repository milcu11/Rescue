@extends('layouts.app')

@section('title', 'GCash Verifications')
@section('page-title', 'GCash Payment Verifications')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">GCash Verifications</li>
@endsection

@section('content')
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
@endif

<div class="card">
  <div class="card-header">
    <h3 class="card-title mb-0"><i class="fas fa-qrcode mr-2"></i>Payments awaiting verification</h3>
  </div>
  <div class="card-body p-0">
    @if($payments->isEmpty())
      <div class="p-4 text-center text-muted">
        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
        <p class="mb-0">No GCash payments are currently awaiting verification.</p>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>Tracking Code</th>
              <th>Donor</th>
              <th>Amount</th>
              <th>GCash Reference</th>
              <th>Proof</th>
              <th>Submitted</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payments as $payment)
              <tr>
                <td><a href="{{ route('donations.show', $payment->donation) }}"><code>{{ $payment->donation->tracking_code }}</code></a></td>
                <td>{{ $payment->donation->donor_name }}</td>
                <td class="font-weight-bold">₱{{ number_format($payment->amount, 2) }}</td>
                <td><code>{{ $payment->gcash_reference_number }}</code></td>
                <td>
                  @if($payment->proof_image_path)
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($payment->proof_image_path) }}" target="_blank" rel="noopener">
                      <i class="fas fa-image mr-1"></i>View
                    </a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>{{ $payment->updated_at->format('M d, Y h:i A') }}</td>
                <td class="drms-table-actions">
                  <form action="{{ route('donations.payment.confirm', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirm this payment as paid?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success drms-icon-action" title="Confirm payment"><i class="fas fa-check"></i></button>
                  </form>
                  <button type="button" class="btn btn-sm btn-danger drms-icon-action" title="Reject" data-toggle="modal" data-target="#rejectModal{{ $payment->id }}"><i class="fas fa-times"></i></button>
                </td>
              </tr>

              <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form action="{{ route('donations.payment.reject', $payment) }}" method="POST">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title">Reject payment — {{ $payment->donation->tracking_code }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Reason (shown to the donor when they resubmit)</label>
                          <textarea name="rejection_reason" class="form-control" rows="2" placeholder="e.g. Reference number not found in our GCash transaction history."></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject payment</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-3">
        {{ $payments->links() }}
      </div>
    @endif
  </div>
</div>
@endsection

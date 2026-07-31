@extends('layouts.app')

@section('title', 'Donation Detail')
@section('page-title', 'Donation Detail')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">{{ $donation->tracking_code }}</li>
@endsection

@section('content')

<div class="row">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
          <i class="bi bi-heart me-2"></i>{{ $donation->tracking_code }}
        </h3>
        @if($donation->status === 'pending')
          <span class="badge bg-warning text-dark fs-6">Pending</span>
        @elseif($donation->status === 'received')
          <span class="badge bg-success fs-6">Received</span>
        @else
          <span class="badge bg-primary fs-6">Distributed</span>
        @endif
      </div>
      <div class="card-body">
        <table class="table table-borderless">
          <tr>
            <th style="width:160px;" class="text-muted">Donor Name</th>
            <td>{{ $donation->donor_name }}</td>
          </tr>
          <tr>
            <th class="text-muted">Contact</th>
            <td>{{ $donation->donor_contact ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Email</th>
            <td>{{ $donation->donor_email ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Type</th>
            <td>
              @if($donation->type === 'monetary')
                <span class="badge bg-success">Monetary</span>
                — ₱{{ number_format($donation->amount, 2) }}
              @else
                <span class="badge bg-info text-dark">In-Kind</span>
              @endif
            </td>
          </tr>
          @if($donation->type === 'in-kind')
          <tr>
            <th class="text-muted">Items</th>
            <td>{{ $donation->items_description }}</td>
          </tr>
          @endif
          <tr>
            <th class="text-muted">Location</th>
            <td>{{ $donation->location ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Received By</th>
            <td>{{ $donation->received_by ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Received At</th>
            <td>{{ $donation->received_at ? $donation->received_at->format('M d, Y h:i A') : '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Notes</th>
            <td>{{ $donation->notes ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Recorded On</th>
            <td>{{ $donation->created_at->format('M d, Y h:i A') }}</td>
          </tr>
        </table>
      </div>
      <div class="card-footer d-flex gap-2 flex-wrap">
        <a href="{{ route('donations.edit', $donation) }}" class="btn btn-primary">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @if($donation->type === 'monetary' && $donation->payment_status !== 'paid')
          <a href="{{ route('donations.payment.create', $donation) }}" class="btn btn-success">
            <i class="fas fa-credit-card mr-1"></i>Pay Now
          </a>
        @endif
        @if($donation->payment_status === 'paid')
          <span class="badge bg-success align-self-center">
            <i class="fas fa-check-circle mr-1"></i>Payment Confirmed
          </span>
        @endif
        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
          Back to list
        </a>
      </div>
    </div>
  </div>
</div>

@endsection

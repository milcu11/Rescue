@extends('layouts.app')

@section('title', 'Track Donation')
@section('page-title', 'Track Your Donation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">Track</li>
@endsection

@section('content')

<div class="card" style="max-width:500px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-search me-2"></i>Track by Donation Code</h3>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('donations.track') }}">
      <div class="input-group">
        <input type="text" name="code" class="form-control" value="{{ $code ?? '' }}" placeholder="e.g. DON-2024-0001">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-search me-1"></i>Track
        </button>
      </div>
    </form>

    @if($error)
      <div class="alert alert-danger mt-3">
        <i class="bi bi-exclamation-circle me-2"></i>{{ $error }}
      </div>
    @endif

    @if($donation)
      <div class="mt-4">
        <table class="table table-borderless">
          <tr>
            <th class="text-muted" style="width:140px;">Tracking Code</th>
            <td><span class="badge bg-dark font-monospace">{{ $donation->tracking_code }}</span></td>
          </tr>
          <tr>
            <th class="text-muted">Donor</th>
            <td>{{ $donation->donor_name }}</td>
          </tr>
          <tr>
            <th class="text-muted">Type</th>
            <td>{{ ucfirst($donation->type) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Status</th>
            <td>
              @if($donation->status === 'pending')
                <span class="badge bg-warning text-dark">Pending — awaiting receipt</span>
              @elseif($donation->status === 'received')
                <span class="badge bg-success">Received — thank you!</span>
              @else
                <span class="badge bg-primary">Distributed to beneficiaries</span>
              @endif
            </td>
          </tr>
          <tr>
            <th class="text-muted">Date Recorded</th>
            <td>{{ $donation->created_at->format('M d, Y') }}</td>
          </tr>
        </table>
      </div>
    @endif

  </div>
</div>

@endsection

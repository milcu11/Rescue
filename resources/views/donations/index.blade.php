@extends('layouts.app')

@section('title', 'Donations')
@section('page-title', 'Donation Tracking')

@section('breadcrumb')
  <li class="breadcrumb-item active">Donations</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-purple text-white">
      <div class="inner">
        <h3>{{ $summary['total'] }}</h3>
        <p>Total Donations</p>
      </div>
      <div class="icon">
        <i class="fas fa-heart"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Overview <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h3>{{ $summary['pending'] }}</h3>
        <p>Pending</p>
      </div>
      <div class="icon">
        <i class="fas fa-hourglass-half"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Pending items <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h3>{{ $summary['received'] }}</h3>
        <p>Received</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Received <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-danger text-white">
      <div class="inner">
        <h3>{{ $summary['distributed'] }}</h3>
        <p>Distributed</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Distributed <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      <i class="bi bi-heart me-2"></i>Donation Records
    </h3>
    <div class="d-flex gap-2">
      <a href="{{ route('donations.track') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-search me-1"></i>Track Donation
      </a>
      <a href="{{ route('donations.create') }}" class="btn btn-sm btn-danger">
        <i class="bi bi-plus-lg me-1"></i>Record Donation
      </a>
    </div>
  </div>
  <div class="card-body">
    <table id="donationsTable" class="table table-bordered table-hover table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Tracking Code</th>
          <th>Donor</th>
          <th>Type</th>
          <th>Details</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($donations as $donation)
        <tr>
          <td>
            <span class="badge bg-dark font-monospace">{{ $donation->tracking_code }}</span>
          </td>
          <td>
            <div class="fw-semibold">{{ $donation->donor_name }}</div>
            @if($donation->donor_contact)
              <small class="text-muted">{{ $donation->donor_contact }}</small>
            @endif
          </td>
          <td>
            @if($donation->type === 'monetary')
              <span class="badge bg-success">Monetary</span>
            @else
              <span class="badge bg-info text-dark">In-Kind</span>
            @endif
          </td>
          <td>
            @if($donation->type === 'monetary')
              ₱{{ number_format($donation->amount, 2) }}
            @else
              {{ Str::limit($donation->items_description, 50) }}
            @endif
          </td>
          <td>
            @if($donation->status === 'pending')
              <span class="badge bg-warning text-dark">Pending</span>
            @elseif($donation->status === 'received')
              <span class="badge bg-success">Received</span>
            @else
              <span class="badge bg-primary">Distributed</span>
            @endif
          </td>
          <td>{{ $donation->created_at->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-info">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this donation record?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
            No donations recorded yet.
            <a href="{{ route('donations.create') }}">Record the first one.</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    var $donTable = $('#donationsTable');
    console.log('donationsTable columns - thead th:', $donTable.find('thead tr th').length, 'first tbody td:', $donTable.find('tbody tr:first td').length);
    safeInit($donTable, {
      pageLength: 25,
      order: [[5, 'desc']], // sort by date newest first
      columnDefs: [
        { orderable: false, targets: [-1] }
      ]
    });
  });
</script>
@endpush

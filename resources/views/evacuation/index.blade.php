@extends('layouts.app')

@section('title', 'Evacuation Centers')
@section('page-title', 'Evacuation Centers')

@section('breadcrumb')
  <li class="breadcrumb-item active">Evacuation Centers</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box bg-danger text-white">
      <div class="inner">
        <h3>{{ $summary['total_evacuees'] }}</h3>
        <p>Total Evacuees</p>
      </div>
      <div class="icon">
        <i class="fas fa-people-group"></i>
      </div>
      <a href="{{ route('evacuation.index') }}" class="small-box-footer">
        View all <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h3>{{ $summary['active'] }}</h3>
        <p>Active Centers</p>
      </div>
      <div class="icon">
        <i class="fas fa-house-user"></i>
      </div>
      <a href="{{ route('evacuation.index') }}" class="small-box-footer">
        Active <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h3>{{ $summary['full'] }}</h3>
        <p>Full Centers</p>
      </div>
      <div class="icon">
        <i class="fas fa-house-chimney-crack"></i>
      </div>
      <a href="{{ route('evacuation.index') }}" class="small-box-footer">
        Full <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-purple text-white">
      <div class="inner">
        <h3>{{ $summary['closed'] }}</h3>
        <p>Closed Centers</p>
      </div>
      <div class="icon">
        <i class="fas fa-house-lock"></i>
      </div>
      <a href="{{ route('evacuation.index') }}" class="small-box-footer">
        Closed <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

{{-- Centers Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      <i class="bi bi-house-door me-2"></i>Evacuation Centers
    </h3>
    <a href="{{ route('evacuation.create') }}" class="btn btn-sm btn-danger">
      <i class="bi bi-plus-lg me-1"></i>Register Center
    </a>
  </div>
  <div class="card-body">
    <table id="evacuationTable" class="table table-bordered table-hover table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Center Name</th>
          <th>Barangay</th>
          <th>Capacity</th>
          <th>Occupancy</th>
          <th>Status</th>
          <th>Contact</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($centers as $center)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>
            <div class="fw-semibold">{{ $center->name }}</div>
            <small class="text-muted">{{ $center->address }}</small>
          </td>
          <td>{{ $center->barangay }}</td>
          <td>{{ number_format($center->capacity) }}</td>
          <td style="min-width:160px;">
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1" style="height:8px;">
                <div class="progress-bar
                  {{ $center->occupancy_percent >= 90 ? 'bg-danger' :
                     ($center->occupancy_percent >= 60 ? 'bg-warning' : 'bg-success') }}"
                  style="width:{{ $center->occupancy_percent }}%">
                </div>
              </div>
              <small>{{ $center->current_occupancy }}/{{ $center->capacity }}</small>
            </div>
          </td>
          <td>
            @if($center->status === 'active')
              <span class="badge bg-success">Active</span>
            @elseif($center->status === 'full')
              <span class="badge bg-warning text-dark">Full</span>
            @else
              <span class="badge bg-secondary">Closed</span>
            @endif
          </td>
          <td>
            <div>{{ $center->contact_person ?? '—' }}</div>
            <small class="text-muted">{{ $center->contact_number ?? '' }}</small>
          </td>
          <td>
            <a href="{{ route('evacuation.show', $center) }}" class="btn btn-sm btn-outline-info" title="View evacuees">
              <i class="bi bi-people"></i>
            </a>
            <a href="{{ route('evacuation.edit', $center) }}" class="btn btn-sm btn-outline-primary" title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('evacuation.destroy', $center) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this center?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
            No evacuation centers registered yet.
            <a href="{{ route('evacuation.create') }}">Register the first one.</a>
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
    var $evacTable = $('#evacuationTable');
    console.log('evacuationTable columns - thead th:', $evacTable.find('thead tr th').length, 'first tbody td:', $evacTable.find('tbody tr:first td').length);
    safeInit($evacTable, {
      pageLength: 25,
      columnDefs: [{ orderable: false, targets: [-1] }]
    });
  });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Relief Operations')
@section('page-title', 'Relief Operations')

@section('breadcrumb')
  <li class="breadcrumb-item active">Relief Operations</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box bg-danger text-white">
      <div class="inner">
        <h3>{{ $summary['total'] }}</h3>
        <p>Total Operations</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        View all <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h3>{{ $summary['active'] }}</h3>
        <p>Active</p>
      </div>
      <div class="icon">
        <i class="fas fa-bolt"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        Active <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h3>{{ $summary['planned'] }}</h3>
        <p>Planned</p>
      </div>
      <div class="icon">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        Planned <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-green2 text-white">
      <div class="inner">
        <h3>{{ $summary['completed'] }}</h3>
        <p>Completed</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        Completed <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      <i class="bi bi-truck me-2"></i>Operations List
    </h3>
    @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
      <a href="{{ route('relief.create') }}" class="btn btn-sm btn-danger">
        <i class="bi bi-plus-lg me-1"></i>New Operation
      </a>
    @endif
  </div>
  <div class="card-body">
    <table id="reliefTable" class="table table-bordered table-hover table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Operation Name</th>
          <th>Incident</th>
          <th>Status</th>
          <th>Start Date</th>
          <th>Distributions</th>
          <th>Beneficiaries</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($operations as $op)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>
            <div class="fw-semibold">{{ $op->name }}</div>
            @if($op->description)
              <small class="text-muted">{{ Str::limit($op->description, 50) }}</small>
            @endif
          </td>
          <td>{{ $op->incident_name ?? '—' }}</td>
          <td>
            @if($op->status === 'active')
              <span class="badge bg-success">Active</span>
            @elseif($op->status === 'planned')
              <span class="badge bg-warning text-dark">Planned</span>
            @elseif($op->status === 'completed')
              <span class="badge bg-primary">Completed</span>
            @else
              <span class="badge bg-secondary">Cancelled</span>
            @endif
          </td>
          <td>{{ $op->start_date->format('M d, Y') }}</td>
          <td class="text-center">{{ $op->distributions->count() }}</td>
          <td class="text-center">{{ number_format($op->total_beneficiaries) }}</td>
          <td>
            <a href="{{ route('relief.show', $op) }}" class="btn btn-sm btn-outline-info" title="View">
              <i class="bi bi-eye"></i>
            </a>
            @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
              <a href="{{ route('relief.edit', $op) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('relief.destroy', $op) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this operation?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
            No relief operations yet.
            @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
              <a href="{{ route('relief.create') }}">Create the first one.</a>
            @endif
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
    var $rel = $('#reliefTable');
    console.log('reliefTable columns - thead th:', $rel.find('thead tr th').length, 'first tbody td:', $rel.find('tbody tr:first td').length);
    safeInit($rel, {
      pageLength: 25,
      order: [[4, 'desc']],
      columnDefs: [{ orderable: false, targets: [-1] }]
    });
  });
</script>
@endpush

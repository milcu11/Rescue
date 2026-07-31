@extends('layouts.app')

@section('title', $evacuation->name)
@section('page-title', $evacuation->name)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('evacuation.index') }}">Evacuation Centers</a></li>
  <li class="breadcrumb-item active">{{ $evacuation->name }}</li>
@endsection

@section('content')

<div class="row">

  {{-- Center Info --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-house me-2"></i>Center Info</h3>
      </div>
      <div class="card-body">
        <table class="table table-borderless table-sm">
          <tr>
            <th class="text-muted">Status</th>
            <td>
              @if($evacuation->status === 'active')
                <span class="badge bg-success">Active</span>
              @elseif($evacuation->status === 'full')
                <span class="badge bg-warning text-dark">Full</span>
              @else
                <span class="badge bg-secondary">Closed</span>
              @endif
            </td>
          </tr>
          <tr>
            <th class="text-muted">Barangay</th>
            <td>{{ $evacuation->barangay }}</td>
          </tr>
          <tr>
            <th class="text-muted">Address</th>
            <td>{{ $evacuation->address }}</td>
          </tr>
          <tr>
            <th class="text-muted">Capacity</th>
            <td>{{ number_format($evacuation->capacity) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Occupancy</th>
            <td>
              {{ number_format($evacuation->current_occupancy) }}
              ({{ $evacuation->occupancy_percent }}%)
            </td>
          </tr>
          <tr>
            <th class="text-muted">Contact</th>
            <td>
              {{ $evacuation->contact_person ?? '—' }}<br>
              <small>{{ $evacuation->contact_number ?? '' }}</small>
            </td>
          </tr>
        </table>

        {{-- Occupancy bar --}}
        <div class="mt-2">
          <div class="progress" style="height:12px;">
            <div class="progress-bar
              {{ $evacuation->occupancy_percent >= 90 ? 'bg-danger' :
                 ($evacuation->occupancy_percent >= 60 ? 'bg-warning' : 'bg-success') }}"
              style="width:{{ $evacuation->occupancy_percent }}%">
              {{ $evacuation->occupancy_percent }}%
            </div>
          </div>
        </div>

        @if($evacuation->notes)
          <p class="mt-3 text-muted small">{{ $evacuation->notes }}</p>
        @endif

        <div class="mt-3 d-flex gap-2">
          <a href="{{ route('evacuation.edit', $evacuation) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit Center
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Evacuees List + Check-in --}}
  <div class="col-md-8">

    {{-- Check-in Form --}}
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-person-plus me-2"></i>Check In Evacuee</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('evacuation.checkin', $evacuation) }}" method="POST">
          @csrf
          <div class="row g-2">
            <div class="col-md-5">
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Full name of head of family *">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="number" name="family_members" min="1" class="form-control @error('family_members') is-invalid @enderror" value="{{ old('family_members', 1) }}" placeholder="Members *">
              @error('family_members')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <input type="text" name="barangay_origin" class="form-control" value="{{ old('barangay_origin') }}" placeholder="From barangay">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-danger w-100"><i class="bi bi-person-plus"></i> Check In</button>
            </div>
            <div class="col-md-6">
              <input type="text" name="needs" class="form-control" value="{{ old('needs') }}" placeholder="Special needs (e.g. medicine, infant formula)">
            </div>
            <div class="col-md-6">
              <input type="text" name="id_presented" class="form-control" value="{{ old('id_presented') }}" placeholder="ID presented (optional)">
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Evacuees Table --}}
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="bi bi-people me-2"></i>Evacuees
          <span class="badge bg-danger ms-1">{{ $evacuation->current_occupancy }}</span>
        </h3>
      </div>
      <div class="card-body">
        <table id="evacueesTable" class="table table-bordered table-hover table-sm align-middle">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Members</th>
              <th>From</th>
              <th>Needs</th>
              <th>Checked In</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($evacuees as $evacuee)
            <tr class="{{ $evacuee->status === 'checked_out' ? 'text-muted' : '' }}">
              <td>{{ $evacuee->name }}</td>
              <td class="text-center">{{ $evacuee->family_members }}</td>
              <td>{{ $evacuee->barangay_origin ?? '—' }}</td>
              <td><small>{{ $evacuee->needs ?? '—' }}</small></td>
              <td><small>{{ $evacuee->checked_in_at->format('M d, h:i A') }}</small></td>
              <td>
                @if($evacuee->status === 'checked_in')
                  <span class="badge bg-success">In</span>
                @else
                  <span class="badge bg-secondary">Out</span>
                @endif
              </td>
              <td>
                @if($evacuee->status === 'checked_in')
                  <form action="{{ route('evacuation.checkout', [$evacuation, $evacuee]) }}" method="POST" onsubmit="return confirm('Check out {{ $evacuee->name }}?')">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Check Out</button>
                  </form>
                @else
                  <small class="text-muted">{{ $evacuee->checked_out_at?->format('M d, h:i A') }}</small>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-3">No evacuees checked in yet.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    var $evacuees = $('#evacueesTable');
    console.log('evacueesTable columns - thead th:', $evacuees.find('thead tr th').length, 'first tbody td:', $evacuees.find('tbody tr:first td').length);
    safeInit($evacuees, {
      pageLength: 25,
      order: [[4, 'desc']],
      columnDefs: [{ orderable: false, targets: [-1] }]
    });
  });
</script>
@endpush

@extends('layouts.app')

@section('title', $relief->name)
@section('page-title', $relief->name)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('relief.index') }}">Relief Operations</a></li>
  <li class="breadcrumb-item active">{{ $relief->name }}</li>
@endsection

@section('content')

<div class="row">

  {{-- Operation Info --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-info-circle me-2"></i>Operation Info</h3>
      </div>
      <div class="card-body">
        <table class="table table-borderless table-sm">
          <tr>
            <th class="text-muted">Status</th>
            <td>
              @if($relief->status === 'active')
                <span class="badge bg-success">Active</span>
              @elseif($relief->status === 'planned')
                <span class="badge bg-warning text-dark">Planned</span>
              @elseif($relief->status === 'completed')
                <span class="badge bg-primary">Completed</span>
              @else
                <span class="badge bg-secondary">Cancelled</span>
              @endif
            </td>
          </tr>
          <tr>
            <th class="text-muted">Incident</th>
            <td>{{ $relief->incident_name ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Start Date</th>
            <td>{{ $relief->start_date->format('M d, Y') }}</td>
          </tr>
          <tr>
            <th class="text-muted">End Date</th>
            <td>{{ $relief->end_date?->format('M d, Y') ?? '—' }}</td>
          </tr>
          <tr>
            <th class="text-muted">Centers Served</th>
            <td>{{ $relief->centers_served }}</td>
          </tr>
          <tr>
            <th class="text-muted">Beneficiaries</th>
            <td>{{ number_format($relief->total_beneficiaries) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Distributions</th>
            <td>{{ $relief->distributions->count() }}</td>
          </tr>
        </table>

        @if($relief->description)
          <p class="text-muted small">{{ $relief->description }}</p>
        @endif

        <a href="{{ route('relief.edit', $relief) }}" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-pencil me-1"></i>Edit Operation</a>
      </div>
    </div>
  </div>

  {{-- Distribution Form + Log --}}
  <div class="col-md-8">

    {{-- Distribute Form --}}
    @if($relief->status !== 'completed' && $relief->status !== 'cancelled')
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-box-arrow-right me-2"></i>Record Distribution</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('relief.distribute', $relief) }}" method="POST">
          @csrf
          <div class="row g-2">

            <div class="col-md-6">
              <label class="form-label fw-semibold">Evacuation Center <span class="text-danger">*</span></label>
              <select name="evacuation_center_id" class="form-select @error('evacuation_center_id') is-invalid @enderror">
                <option value="">Select center</option>
                @foreach($centers as $center)
                  <option value="{{ $center->id }}" {{ old('evacuation_center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }} ({{ $center->current_occupancy }} evacuees)</option>
                @endforeach
              </select>
              @error('evacuation_center_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Item from Inventory <span class="text-danger">*</span></label>
              <select name="inventory_item_id" class="form-select @error('inventory_item_id') is-invalid @enderror">
                <option value="">Select item</option>
                @foreach($items as $item)
                  <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }} ({{ $item->quantity }} {{ $item->unit }} left)</option>
                @endforeach
              </select>
              @error('inventory_item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Qty to Distribute <span class="text-danger">*</span></label>
              <input type="number" name="quantity_distributed" min="1" class="form-control @error('quantity_distributed') is-invalid @enderror" value="{{ old('quantity_distributed') }}" placeholder="0">
              @error('quantity_distributed')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Beneficiaries Count <span class="text-danger">*</span></label>
              <input type="number" name="beneficiaries_count" min="0" class="form-control @error('beneficiaries_count') is-invalid @enderror" value="{{ old('beneficiaries_count') }}" placeholder="0">
              @error('beneficiaries_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4 d-flex align-items-end">
              <button type="submit" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i>Record</button>
            </div>

            <div class="col-12">
              <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Notes (optional)">
            </div>

          </div>
        </form>
      </div>
    </div>
    @endif

    {{-- Distribution Log --}}
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-list-check me-2"></i>Distribution Log</h3>
      </div>
      <div class="card-body">
        <table id="distTable" class="table table-bordered table-sm table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Center</th>
              <th>Item</th>
              <th>Qty</th>
              <th>Beneficiaries</th>
              <th>Date</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @forelse($relief->distributions as $dist)
            <tr>
              <td>{{ $dist->center->name }}</td>
              <td>{{ $dist->item->name }}</td>
              <td>{{ number_format($dist->quantity_distributed) }} {{ $dist->item->unit }}</td>
              <td>{{ number_format($dist->beneficiaries_count) }}</td>
              <td>{{ $dist->distributed_at->format('M d, Y h:i A') }}</td>
              <td><small class="text-muted">{{ $dist->notes ?? '—' }}</small></td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-3">No distributions recorded yet.</td>
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
    var $dist = $('#distTable');
    console.log('distTable columns - thead th:', $dist.find('thead tr th').length, 'first tbody td:', $dist.find('tbody tr:first td').length);
    safeInit($dist, {
      pageLength: 25,
      order: [[4, 'desc']],
      columnDefs: [{ orderable: false, targets: [-1] }]
    });
  });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory Management')

@section('breadcrumb')
  <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h3>{{ $summary['total'] }}</h3>
        <p>Total Items</p>
      </div>
      <div class="icon">
        <i class="fas fa-box"></i>
      </div>
      <a href="{{ route('inventory.index') }}" class="small-box-footer">
        View all <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-green2 text-white">
      <div class="inner">
        <h3>{{ $summary['available'] }}</h3>
        <p>Available</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('inventory.index') }}" class="small-box-footer">
        In stock <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h3>{{ $summary['low_stock'] }}</h3>
        <p>Low Stock</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <a href="{{ route('inventory.index') }}" class="small-box-footer">
        Needs restock <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-danger text-white">
      <div class="inner">
        <h3>{{ $summary['depleted'] }}</h3>
        <p>Depleted</p>
      </div>
      <div class="icon">
        <i class="fas fa-xmark-circle"></i>
      </div>
      <a href="{{ route('inventory.index') }}" class="small-box-footer">
        Out of stock <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      <i class="bi bi-box-seam me-2"></i>Inventory Items
    </h3>
    <a href="{{ route('inventory.create') }}" class="btn btn-sm btn-danger">
      <i class="bi bi-plus-lg me-1"></i>Add Item
    </a>
  </div>
  <div class="card-body">
    <table id="inventoryTable" class="table table-bordered table-hover table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>Min. Threshold</th>
          <th>Location</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td><span class="badge bg-secondary">{{ ucfirst($item->category) }}</span></td>
          <td>{{ number_format($item->quantity) }}</td>
          <td>{{ $item->unit }}</td>
          <td>{{ number_format($item->minimum_threshold) }}</td>
          <td>{{ $item->location ?? '—' }}</td>
          <td>
            @if($item->status === 'available')
              <span class="badge bg-success">Available</span>
            @elseif($item->status === 'low_stock')
              <span class="badge bg-warning text-dark">Low Stock</span>
            @else
              <span class="badge bg-danger">Depleted</span>
            @endif
          </td>
          <td>
            <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item from inventory?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
            No inventory items yet.
            <a href="{{ route('inventory.create') }}">Add the first one.</a>
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
    var $inv = $('#inventoryTable');
    console.log('inventoryTable columns - thead th:', $inv.find('thead tr th').length, 'first tbody td:', $inv.find('tbody tr:first td').length);
    safeInit($inv, {
      pageLength: 25,
      order: [[7, 'asc']],
      columnDefs: [
        { orderable: false, targets: [-1] }
      ]
    });
  });
</script>
@endpush

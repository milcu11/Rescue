@extends('layouts.app')

@section('title', 'Edit Item')
@section('page-title', 'Edit Inventory Item')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-pencil me-2"></i>Edit: {{ $inventoryItem->name }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('inventory.update', $inventoryItem) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $inventoryItem->name) }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
          <select name="category" class="form-select @error('category') is-invalid @enderror">
            @foreach(['food','medicine','clothing','tools','other'] as $cat)
              <option value="{{ $cat }}"
                {{ old('category', $inventoryItem->category) === $cat ? 'selected' : '' }}>
                {{ ucfirst($cat) }}
              </option>
            @endforeach
          </select>
          @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
          <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                 value="{{ old('unit', $inventoryItem->unit) }}">
          @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
          <input type="number" name="quantity" min="0"
                 class="form-control @error('quantity') is-invalid @enderror"
                 value="{{ old('quantity', $inventoryItem->quantity) }}">
          @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Minimum Threshold <span class="text-danger">*</span></label>
          <input type="number" name="minimum_threshold" min="0"
                 class="form-control @error('minimum_threshold') is-invalid @enderror"
                 value="{{ old('minimum_threshold', $inventoryItem->minimum_threshold) }}">
          @error('minimum_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Storage Location</label>
        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
               value="{{ old('location', $inventoryItem->location) }}">
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $inventoryItem->notes) }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-check-lg me-1"></i>Update Item
        </button>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>

@endsection

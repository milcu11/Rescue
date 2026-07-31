@extends('layouts.app')

@section('title', 'Edit Center')
@section('page-title', 'Edit Evacuation Center')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('evacuation.index') }}">Evacuation Centers</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-pencil me-2"></i>Edit: {{ $evacuation->name }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('evacuation.update', $evacuation) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Center Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $evacuation->name) }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Barangay <span class="text-danger">*</span></label>
          <input type="text" name="barangay" class="form-control" value="{{ old('barangay', $evacuation->barangay) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Capacity <span class="text-danger">*</span></label>
          <input type="number" name="capacity" min="1" class="form-control" value="{{ old('capacity', $evacuation->capacity) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
        <input type="text" name="address" class="form-control" value="{{ old('address', $evacuation->address) }}">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select">
          @foreach(['active','full','closed'] as $s)
            <option value="{{ $s }}" {{ old('status', $evacuation->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Person</label>
          <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $evacuation->contact_person) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Number</label>
          <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $evacuation->contact_number) }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Latitude</label>
          <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $evacuation->latitude) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Longitude</label>
          <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $evacuation->longitude) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $evacuation->notes) }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Update Center</button>
        <a href="{{ route('evacuation.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection

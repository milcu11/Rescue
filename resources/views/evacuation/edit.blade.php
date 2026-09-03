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
          @foreach(['open','full','closed'] as $s)
            <option value="{{ $s }}" {{ old('status', $evacuation->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Current Occupancy</label>
          <input type="number" name="current_occupancy" min="0" class="form-control" value="{{ old('current_occupancy', $evacuation->current_occupancy) }}">
          <small class="text-muted">Headcount physically inside</small>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Families Registered</label>
          <input type="number" name="families_registered" min="0" class="form-control" value="{{ old('families_registered', $evacuation->families_registered) }}">
          <small class="text-muted">Pre-registered families count</small>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Medical Needs Flagged</label>
          <input type="number" name="medical_needs_count" min="0" class="form-control" value="{{ old('medical_needs_count', $evacuation->medical_needs_count) }}">
          <small class="text-muted">Cases needing medical attention</small>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Person</label>
          <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $evacuation->contact_person) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Phone</label>
          <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $evacuation->contact_phone) }}">
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
        <label class="form-label fw-semibold">Check-in Procedures for Families</label>
        <textarea name="intake_procedures" rows="4" class="form-control">{{ old('intake_procedures', $evacuation->intake_procedures) }}</textarea>
        <small class="text-muted">Instructions shown to families on the public evacuation page.</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Required Documents & Items</label>
        <textarea name="required_items" rows="3" class="form-control">{{ old('required_items', $evacuation->required_items) }}</textarea>
        <small class="text-muted">What families must bring to be admitted.</small>
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

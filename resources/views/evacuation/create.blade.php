@extends('layouts.app')

@section('title', 'Register Center')
@section('page-title', 'Register Evacuation Center')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('evacuation.index') }}">Evacuation Centers</a></li>
  <li class="breadcrumb-item active">Register</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-house-add me-2"></i>New Evacuation Center</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('evacuation.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Center Name <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}"
               placeholder="e.g. Baras Sports Complex">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Barangay <span class="text-danger">*</span></label>
          <input type="text" name="barangay"
                 class="form-control @error('barangay') is-invalid @enderror"
                 value="{{ old('barangay') }}" placeholder="e.g. Baras">
          @error('barangay')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Capacity <span class="text-danger">*</span></label>
          <input type="number" name="capacity" min="1"
                 class="form-control @error('capacity') is-invalid @enderror"
                 value="{{ old('capacity') }}" placeholder="Max persons">
          @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
        <input type="text" name="address"
               class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address') }}"
               placeholder="Street, Barangay, Municipality">
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Person</label>
          <input type="text" name="contact_person" class="form-control"
                 value="{{ old('contact_person') }}" placeholder="Name of person-in-charge">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Phone</label>
          <input type="text" name="contact_phone" class="form-control"
                 value="{{ old('contact_phone') }}" placeholder="09XX XXX XXXX">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Latitude <span class="text-muted">(optional)</span></label>
          <input type="text" name="latitude" class="form-control"
                 value="{{ old('latitude') }}" placeholder="e.g. 14.4500">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Longitude <span class="text-muted">(optional)</span></label>
          <input type="text" name="longitude" class="form-control"
                 value="{{ old('longitude') }}" placeholder="e.g. 121.1900">
        </div>
      </div>
      <small class="text-muted d-block mb-3">
        <i class="bi bi-info-circle me-1"></i>
        Coordinates are used for the map view. You can get them from Google Maps by right-clicking your location.
      </small>

      <div class="mb-3">
        <label class="form-label fw-semibold">Check-in Procedures for Families</label>
        <textarea name="intake_procedures" rows="4" class="form-control"
                  placeholder="Step-by-step instructions (e.g. Go only if status is OPEN, Report to registration desk, Present valid ID, etc.)">{{ old('intake_procedures') }}</textarea>
        <small class="text-muted">Instructions shown to families on the public evacuation page.</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Required Documents & Items</label>
        <textarea name="required_items" rows="3" class="form-control"
                  placeholder="Valid ID, clothes, hygiene kit, medicines, etc.">{{ old('required_items') }}</textarea>
        <small class="text-muted">What families must bring to be admitted.</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control"
                  placeholder="Special instructions, facilities available, etc.">{{ old('notes') }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-check-lg me-1"></i>Register Center
        </button>
        <a href="{{ route('evacuation.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection

@extends('layouts.app')

@section('title', 'Edit Donation')
@section('page-title', 'Edit Donation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title">
      <i class="bi bi-pencil me-2"></i>Edit: {{ $donation->tracking_code }}
    </h3>
  </div>
  <div class="card-body">
    <form action="{{ route('donations.update', $donation) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Donor Name <span class="text-danger">*</span></label>
        <input type="text" name="donor_name"
               class="form-control @error('donor_name') is-invalid @enderror"
               value="{{ old('donor_name', $donation->donor_name) }}">
        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Number</label>
          <input type="text" name="donor_contact"
                 class="form-control"
                 value="{{ old('donor_contact', $donation->donor_contact) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Email Address</label>
          <input type="email" name="donor_email"
                 class="form-control"
                 value="{{ old('donor_email', $donation->donor_email) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Donation Type <span class="text-danger">*</span></label>
        <div class="d-flex gap-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type"
                   id="type_inkind" value="in-kind"
                   {{ old('type', $donation->type) === 'in-kind' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_inkind">In-Kind</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type"
                   id="type_monetary" value="monetary"
                   {{ old('type', $donation->type) === 'monetary' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_monetary">Monetary</label>
          </div>
        </div>
      </div>

      <div id="inkind_fields" style="{{ old('type', $donation->type) === 'monetary' ? 'display:none' : '' }}">
        <div class="mb-3">
          <label class="form-label fw-semibold">Items Description</label>
          <textarea name="items_description" rows="3" class="form-control @error('items_description') is-invalid @enderror">{{ old('items_description', $donation->items_description) }}</textarea>
          @error('items_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div id="monetary_fields" style="{{ old('type', $donation->type) === 'monetary' ? '' : 'display:none' }}">
        <div class="mb-3">
          <label class="form-label fw-semibold">Amount (₱)</label>
          <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="number" name="amount" step="0.01" min="0" class="form-control" value="{{ old('amount', $donation->amount) }}">
          </div>
        </div>
      </div>

      <hr class="my-3">
      <h6 class="text-muted text-uppercase mb-3" style="font-size:11px;letter-spacing:.05em;">Status Update</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
          @foreach(['pending','received','distributed'] as $s)
            <option value="{{ $s }}" {{ old('status', $donation->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Received By</label>
          <input type="text" name="received_by" class="form-control" value="{{ old('received_by', $donation->received_by) }}" placeholder="Name of staff who received it">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Received At</label>
          <input type="datetime-local" name="received_at" class="form-control" value="{{ old('received_at', $donation->received_at?->format('Y-m-d\TH:i')) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Location</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $donation->location) }}">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $donation->notes) }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-check-lg me-1"></i>Update Donation
        </button>
        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection

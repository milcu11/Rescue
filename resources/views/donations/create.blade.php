@extends('layouts.app')

@section('title', 'Record Donation')
@section('page-title', 'Record Donation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></li>
  <li class="breadcrumb-item active">Record Donation</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-heart me-2"></i>New Donation Record</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('donations.store') }}" method="POST">
      @csrf

      <h6 class="text-muted text-uppercase mb-3" style="font-size:11px;letter-spacing:.05em;">Donor Information</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold">Donor Name <span class="text-danger">*</span></label>
        <input type="text" name="donor_name"
               class="form-control @error('donor_name') is-invalid @enderror"
               value="{{ old('donor_name') }}" placeholder="Full name or organization">
        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Contact Number</label>
          <input type="text" name="donor_contact"
                 class="form-control @error('donor_contact') is-invalid @enderror"
                 value="{{ old('donor_contact') }}" placeholder="09XX XXX XXXX">
          @error('donor_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Email Address</label>
          <input type="email" name="donor_email"
                 class="form-control @error('donor_email') is-invalid @enderror"
                 value="{{ old('donor_email') }}" placeholder="optional@email.com">
          @error('donor_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <hr class="my-3">
      <h6 class="text-muted text-uppercase mb-3" style="font-size:11px;letter-spacing:.05em;">Donation Details</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold">Donation Type <span class="text-danger">*</span></label>
        <div class="d-flex gap-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type"
                   id="type_inkind" value="in-kind"
                   {{ old('type', 'in-kind') === 'in-kind' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_inkind">In-Kind (goods/supplies)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type"
                   id="type_monetary" value="monetary"
                   {{ old('type') === 'monetary' ? 'checked' : '' }}>
            <label class="form-check-label" for="type_monetary">Monetary (cash)</label>
          </div>
        </div>
        @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>

      {{-- In-kind fields --}}
      <div id="inkind_fields">
        <div class="mb-3">
          <label class="form-label fw-semibold">Items Description <span class="text-danger">*</span></label>
          <textarea name="items_description" rows="3"
                    class="form-control @error('items_description') is-invalid @enderror"
                    placeholder="e.g. 50 sacks of rice, 20 boxes of sardines, 10 boxes of bottled water">{{ old('items_description') }}</textarea>
          @error('items_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Monetary fields --}}
      <div id="monetary_fields" style="display:none;">
        <div class="mb-3">
          <label class="form-label fw-semibold">Amount (₱) <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="number" name="amount" step="0.01" min="0"
                   class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount') }}" placeholder="0.00">
          </div>
          @error('amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Drop-off Location</label>
        <input type="text" name="location"
               class="form-control @error('location') is-invalid @enderror"
               value="{{ old('location') }}" placeholder="e.g. Municipal Hall, Barangay Hall">
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror" placeholder="Any additional notes">{{ old('notes') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-check-lg me-1"></i>Save Donation
        </button>
        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Toggle in-kind vs monetary fields
  document.querySelectorAll('input[name="type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      const isMonetary = this.value === 'monetary';
      document.getElementById('monetary_fields').style.display = isMonetary ? 'block' : 'none';
      document.getElementById('inkind_fields').style.display   = isMonetary ? 'none'  : 'block';
    });
  });
</script>
@endpush

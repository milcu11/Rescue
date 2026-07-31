@extends('layouts.app')

@section('title', 'New Operation')
@section('page-title', 'Create Relief Operation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('relief.index') }}">Relief Operations</a></li>
  <li class="breadcrumb-item active">New</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-truck me-2"></i>New Relief Operation</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('relief.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Operation Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Typhoon Carina Relief — Wave 1">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror" placeholder="Brief description of this operation">{{ old('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach(['planned','active','completed','cancelled'] as $s)
              <option value="{{ $s }}" {{ old('status', 'planned') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
          @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Linked Incident</label>
          <input type="text" name="incident_name" class="form-control" value="{{ old('incident_name') }}" placeholder="e.g. Typhoon Carina (from Group 1)">
          <div class="form-text">Will be linked to Group 1's incident system later.</div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
          <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}">
          @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">End Date</label>
          <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
          @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Create Operation</button>
        <a href="{{ route('relief.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection

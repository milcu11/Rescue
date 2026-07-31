@extends('layouts.app')

@section('title', 'Edit Operation')
@section('page-title', 'Edit Relief Operation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('relief.index') }}">Relief Operations</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card" style="max-width:700px;">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-pencil me-2"></i>Edit: {{ $relief->name }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('relief.update', $relief) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Operation Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $relief->name) }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="2" class="form-control">{{ old('description', $relief->description) }}</textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select">
            @foreach(['planned','active','completed','cancelled'] as $s)
              <option value="{{ $s }}" {{ old('status', $relief->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Linked Incident</label>
          <input type="text" name="incident_name" class="form-control" value="{{ old('incident_name', $relief->incident_name) }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
          <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $relief->start_date->format('Y-m-d')) }}">
          @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">End Date</label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $relief->end_date?->format('Y-m-d')) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $relief->notes) }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Update Operation</button>
        <a href="{{ route('relief.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

@endsection

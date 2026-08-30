@extends('layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')

@section('breadcrumb')
  <li class="breadcrumb-item active">Audit Trail</li>
@endsection

@section('content')

{{-- Filters --}}
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filters</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('audit.index') }}">
      <div class="row">
        <div class="col-md-2">
          <label class="font-weight-bold" style="font-size:12px;">Module</label>
          <select name="module" class="form-control form-control-sm">
            <option value="">All modules</option>
            @foreach($modules as $m)
              <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_',' ',$m)) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="font-weight-bold" style="font-size:12px;">Action</label>
          <select name="action" class="form-control form-control-sm">
            <option value="">All actions</option>
            @foreach($actions as $a)
              <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>
                {{ ucfirst($a) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="font-weight-bold" style="font-size:12px;">User</label>
          <input type="text" name="user" class="form-control form-control-sm"
                 value="{{ request('user') }}" placeholder="Search by name">
        </div>
        <div class="col-md-2">
          <label class="font-weight-bold" style="font-size:12px;">From</label>
          <input type="date" name="from" class="form-control form-control-sm"
                 value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
          <label class="font-weight-bold" style="font-size:12px;">To</label>
          <input type="date" name="to" class="form-control form-control-sm"
                 value="{{ request('to') }}">
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-sm btn-danger btn-block">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
      @if(request()->hasAny(['module','action','user','from','to']))
        <div class="mt-2">
          <a href="{{ route('audit.index') }}" class="text-muted" style="font-size:12px;">
            <i class="fas fa-times mr-1"></i>Clear filters
          </a>
        </div>
      @endif
    </form>
  </div>
</div>

{{-- Log Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      <i class="fas fa-history mr-2"></i>Activity Log
      <span class="badge badge-secondary ml-1">{{ $logs->total() }} records</span>
    </h3>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-sm mb-0">
      <thead class="thead-dark">
        <tr>
          <th style="width:140px;">Date &amp; Time</th>
          <th style="width:120px;">User</th>
          <th style="width:80px;">Role</th>
          <th style="width:70px;">Action</th>
          <th style="width:100px;">Module</th>
          <th>Record</th>
          <th style="width:80px;">IP</th>
          <th style="width:50px;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td>
              <small>{{ $log->created_at->format('M d, Y') }}</small><br>
              <small class="text-muted">{{ $log->created_at->format('h:i:s A') }}</small>
            </td>
            <td>
              <span class="font-weight-bold">{{ $log->user_name }}</span>
            </td>
            <td>
              <small class="text-muted">
                {{ ucfirst(str_replace('_',' ',$log->user_role)) }}
              </small>
            </td>
            <td>
              <span class="badge badge-{{ $log->action_badge }}">
                {{ ucfirst($log->action) }}
              </span>
            </td>
            <td>
              <span class="badge badge-secondary">
                {{ ucfirst(str_replace('_',' ',$log->module)) }}
              </span>
            </td>
            <td>
              {{ $log->record_label ?? '—' }}
              @if($log->record_id)
                <small class="text-muted">#{{ $log->record_id }}</small>
              @endif
            </td>
            <td><small class="text-muted">{{ $log->ip_address ?? '—' }}</small></td>
            <td>
              <a href="{{ route('audit.show', $log) }}"
                 class="btn btn-xs btn-outline-secondary">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              <i class="fas fa-history fa-2x d-block mb-2"></i>
              No activity logged yet.
            </td>
          </tr>
        @endforelse
      </tbody>
      </table>
    </div>
  </div>
  @if($logs->hasPages())
    <div class="card-footer clearfix">
      {{ $logs->links('pagination::bootstrap-4') }}
    </div>
  @endif
</div>

@endsection

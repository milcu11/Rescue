@extends('layouts.app')

@section('title', 'Audit Log Detail')
@section('page-title', 'Audit Log Detail')

@section('breadcrumb')
  <li class="breadcrumb-item">
    <a href="{{ route('audit.index') }}">Audit Trail</a>
  </li>
  <li class="breadcrumb-item active">#{{ $auditLog->id }}</li>
@endsection

@section('content')

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-2"></i>Log Entry #{{ $auditLog->id }}
        </h3>
      </div>
      <div class="card-body">
        <table class="table table-borderless table-sm">
          <tr>
            <th class="text-muted" style="width:130px;">Date &amp; Time</th>
            <td>{{ $auditLog->created_at->format('M d, Y h:i:s A') }}</td>
          </tr>
          <tr>
            <th class="text-muted">User</th>
            <td>{{ $auditLog->user_name }}</td>
          </tr>
          <tr>
            <th class="text-muted">Role</th>
            <td>{{ ucfirst(str_replace('_',' ',$auditLog->user_role)) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Action</th>
            <td>
              <span class="badge badge-{{ $auditLog->action_badge }}">
                {{ ucfirst($auditLog->action) }}
              </span>
            </td>
          </tr>
          <tr>
            <th class="text-muted">Module</th>
            <td>{{ ucfirst(str_replace('_',' ',$auditLog->module)) }}</td>
          </tr>
          <tr>
            <th class="text-muted">Record</th>
            <td>
              {{ $auditLog->record_label ?? '—' }}
              @if($auditLog->record_id)
                <small class="text-muted">(ID #{{ $auditLog->record_id }})</small>
              @endif
            </td>
          </tr>
          <tr>
            <th class="text-muted">IP Address</th>
            <td>{{ $auditLog->ip_address ?? '—' }}</td>
          </tr>
          @if($auditLog->notes)
            <tr>
              <th class="text-muted">Notes</th>
              <td>{{ $auditLog->notes }}</td>
            </tr>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-arrow-left mr-1"></i>Back to log
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-6">

    @if($auditLog->old_values || $auditLog->new_values)
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-exchange-alt mr-2"></i>Changes
        </h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead class="thead-dark">
            <tr>
              <th>Field</th>
              @if($auditLog->old_values)
                <th class="text-danger">Before</th>
              @endif
              @if($auditLog->new_values)
                <th class="text-success">After</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @php
              $fields = array_unique(array_merge(
                array_keys($auditLog->old_values ?? []),
                array_keys($auditLog->new_values ?? [])
              ));
              $skip = ['updated_at','created_at','deleted_at','password','remember_token'];
            @endphp
            @foreach($fields as $field)
              @if(!in_array($field, $skip))
                <tr>
                  <td>
                    <small class="font-weight-bold">
                      {{ ucfirst(str_replace('_',' ',$field)) }}
                    </small>
                  </td>
                  @if($auditLog->old_values)
                    <td>
                      <small class="text-danger">
                        {{ $auditLog->old_values[$field] ?? '—' }}
                      </small>
                    </td>
                  @endif
                  @if($auditLog->new_values)
                    <td>
                      <small class="text-success">
                        {{ $auditLog->new_values[$field] ?? '—' }}
                      </small>
                    </td>
                  @endif
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @else
      <div class="card">
        <div class="card-body text-muted text-center py-4">
          <i class="fas fa-info-circle fa-2x d-block mb-2"></i>
          No field changes recorded for this action.
        </div>
      </div>
    @endif

  </div>
</div>

@endsection

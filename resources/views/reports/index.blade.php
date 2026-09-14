@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb')
  <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')

<form method="GET" action="{{ route('reports.index') }}" class="card mb-3">
  <div class="card-body">
    <div class="form-row align-items-end">
      <div class="form-group col-md-2 mb-2">
        <label for="report-from">From</label>
        <input id="report-from" type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-to">To</label>
        <input id="report-to" type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-status">Status</label>
        <input id="report-status" type="text" name="status" value="{{ request('status') }}" class="form-control form-control-sm" placeholder="e.g. received">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-category">Category</label>
        <input id="report-category" type="text" name="category" value="{{ request('category') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-item">Item ID</label>
        <input id="report-item" type="number" min="1" name="item" value="{{ request('item') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-center">Center ID</label>
        <input id="report-center" type="number" min="1" name="center" value="{{ request('center') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-module">Audit Module</label>
        <input id="report-module" type="text" name="module" value="{{ request('module') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2">
        <label for="report-action">Audit Action</label>
        <input id="report-action" type="text" name="action" value="{{ request('action') }}" class="form-control form-control-sm">
      </div>
      <div class="form-group col-md-2 mb-2 d-flex" style="gap:6px;">
        <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="fas fa-filter mr-1"></i>Apply</button>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary" title="Clear filters"><i class="fas fa-times"></i></a>
      </div>
    </div>
  </div>
</form>

<div class="row mb-3">
  <div class="col-md-6">
    <div class="card mb-0">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div><strong><i class="fas fa-exchange-alt mr-2"></i>Stock Movement Report</strong><br><small class="text-muted">Inbound and outbound inventory transactions</small></div>
        <div class="d-flex" style="gap:6px;">
          <a href="{{ route('reports.movements.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print"><i class="fas fa-print"></i></a>
          <a href="{{ route('reports.movements.excel', request()->query()) }}" class="btn btn-sm btn-outline-success" title="Excel"><i class="fas fa-file-excel"></i></a>
          <a href="{{ route('reports.movements.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger" title="PDF"><i class="fas fa-file-pdf"></i></a>
        </div>
      </div>
    </div>
  </div>
  @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo']))
  <div class="col-md-6">
    <div class="card mb-0">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div><strong><i class="fas fa-history mr-2"></i>Audit Trail Report</strong><br><small class="text-muted">Traceable system actions and changes</small></div>
        <div class="d-flex" style="gap:6px;">
          <a href="{{ route('reports.audit.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print"><i class="fas fa-print"></i></a>
          <a href="{{ route('reports.audit.excel', request()->query()) }}" class="btn btn-sm btn-outline-success" title="Excel"><i class="fas fa-file-excel"></i></a>
          <a href="{{ route('reports.audit.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger" title="PDF"><i class="fas fa-file-pdf"></i></a>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<div class="row">

  {{-- Inventory Report --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-success text-white">
        <h3 class="card-title">
          <i class="fas fa-boxes mr-2"></i>Inventory Report
        </h3>
      </div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col-3">
            <div class="h4 mb-0">{{ $summary['inventory']['total'] }}</div>
            <small class="text-muted">Total</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-success">{{ $summary['inventory']['available'] }}</div>
            <small class="text-muted">Available</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-warning">{{ $summary['inventory']['low_stock'] }}</div>
            <small class="text-muted">Low Stock</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-danger">{{ $summary['inventory']['depleted'] }}</div>
            <small class="text-muted">Depleted</small>
          </div>
        </div>
        <div class="d-flex gap-2 justify-content-center" style="gap:6px;">
          <a href="{{ route('reports.inventory.print', request()->query()) }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.inventory.excel', request()->query()) }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.inventory.pdf', request()->query()) }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
  {{-- Donations Report --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white" style="background:#4A235A;">
        <h3 class="card-title">
          <i class="fas fa-donate mr-2"></i>Donations Report
        </h3>
      </div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col-3">
            <div class="h4 mb-0">{{ $summary['donations']['total'] }}</div>
            <small class="text-muted">Total</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-warning">{{ $summary['donations']['pending'] }}</div>
            <small class="text-muted">Pending</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-success">{{ $summary['donations']['received'] }}</div>
            <small class="text-muted">Received</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-primary">{{ $summary['donations']['distributed'] }}</div>
            <small class="text-muted">Distributed</small>
          </div>
        </div>
        <div class="text-center mb-3">
          <small class="text-muted">Total monetary donations: </small>
          <strong>₱{{ number_format($summary['donations']['monetary_total'], 2) }}</strong>
        </div>
        <div class="d-flex justify-content-center" style="gap:6px;">
          <a href="{{ route('reports.donations.print', request()->query()) }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.donations.excel', request()->query()) }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.donations.pdf', request()->query()) }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  @endif

  @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
  {{-- Evacuation Report --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white" style="background:#D35400;">
        <h3 class="card-title">
          <i class="fas fa-home mr-2"></i>Evacuation Report
        </h3>
      </div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col-3">
            <div class="h4 mb-0">{{ $summary['evacuation']['total'] }}</div>
            <small class="text-muted">Centers</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-success">{{ $summary['evacuation']['active'] }}</div>
            <small class="text-muted">Active</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-warning">{{ $summary['evacuation']['full'] }}</div>
            <small class="text-muted">Full</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-danger">{{ $summary['evacuation']['occupancy'] }}</div>
            <small class="text-muted">Evacuees</small>
          </div>
        </div>
        @php
          $pct = $summary['evacuation']['capacity'] > 0
            ? round(($summary['evacuation']['occupancy'] / $summary['evacuation']['capacity']) * 100)
            : 0;
        @endphp
        <div class="progress mb-3" style="height:8px;">
          <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 60 ? 'bg-warning' : 'bg-success') }}"
               style="width:{{ $pct }}%"></div>
        </div>
        <div class="text-center mb-3">
          <small class="text-muted">Overall occupancy: </small>
          <strong>{{ $pct }}%</strong>
        </div>
        <div class="d-flex justify-content-center" style="gap:6px;">
          <a href="{{ route('reports.evacuation.print', request()->query()) }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.evacuation.excel', request()->query()) }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.evacuation.pdf', request()->query()) }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  @endif

  @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
  {{-- Relief Report --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white" style="background:#C0392B;">
        <h3 class="card-title">
          <i class="fas fa-truck mr-2"></i>Relief Operations Report
        </h3>
      </div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col-3">
            <div class="h4 mb-0">{{ $summary['relief']['total'] }}</div>
            <small class="text-muted">Operations</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-success">{{ $summary['relief']['active'] }}</div>
            <small class="text-muted">Active</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0 text-primary">{{ $summary['relief']['completed'] }}</div>
            <small class="text-muted">Completed</small>
          </div>
          <div class="col-3">
            <div class="h4 mb-0">{{ $summary['relief']['distributions'] }}</div>
            <small class="text-muted">Distributions</small>
          </div>
        </div>
        <div class="text-center mb-3">
          <small class="text-muted">Total beneficiaries served: </small>
          <strong>{{ number_format($summary['relief']['beneficiaries']) }}</strong>
        </div>
        <div class="d-flex justify-content-center" style="gap:6px;">
          <a href="{{ route('reports.relief.print', request()->query()) }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.relief.excel', request()->query()) }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.relief.pdf', request()->query()) }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  @endif

</div>

@endsection

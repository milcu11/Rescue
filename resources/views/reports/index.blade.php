@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb')
  <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')

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
          <a href="{{ route('reports.inventory.print') }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.inventory.excel') }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.inventory.pdf') }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

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
          <a href="{{ route('reports.donations.print') }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.donations.excel') }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.donations.pdf') }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

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
          <a href="{{ route('reports.evacuation.print') }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.evacuation.excel') }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.evacuation.pdf') }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

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
          <a href="{{ route('reports.relief.print') }}" target="_blank"
             class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print mr-1"></i>Print
          </a>
          <a href="{{ route('reports.relief.excel') }}"
             class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i>Excel
          </a>
          <a href="{{ route('reports.relief.pdf') }}"
             class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf mr-1"></i>PDF
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

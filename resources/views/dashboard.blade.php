@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div class="card mb-3">
  <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-2">
    <div>
      <strong>Operational snapshot</strong>
      <span class="text-muted ml-2">Database totals for the selected activity window.</span>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="form-inline mt-2 mt-md-0">
      <label for="dashboardPeriod" class="mr-2 mb-0 text-muted">Activity period</label>
      <select id="dashboardPeriod" name="period" class="form-control form-control-sm" onchange="this.form.submit()">
        <option value="7" {{ ($stats['period'] ?? '30') === '7' ? 'selected' : '' }}>Last 7 days</option>
        <option value="30" {{ ($stats['period'] ?? '30') === '30' ? 'selected' : '' }}>Last 30 days</option>
        <option value="90" {{ ($stats['period'] ?? '30') === '90' ? 'selected' : '' }}>Last 90 days</option>
        <option value="all" {{ ($stats['period'] ?? '30') === 'all' ? 'selected' : '' }}>All time</option>
      </select>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-red text-white">
      <div class="inner">
        <h3>{{ $stats['active_ops'] ?? 0 }}</h3>
        <p>Active Relief Ops</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        Open list <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box small-box-brown text-white">
      <div class="inner">
        <h3>{{ $stats['total_distributions'] ?? 0 }}</h3>
        <p>Relief Distributions</p>
      </div>
      <div class="icon">
        <i class="fas fa-hands-helping"></i>
      </div>
      <a href="{{ route('relief.index') }}" class="small-box-footer">
        Distributions <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box small-box-green text-white">
      <div class="inner">
        <h3>{{ $stats['occupancy_percent'] ?? 0 }}%</h3>
        <p>Evacuation Occupancy</p>
      </div>
      <div class="icon">
        <i class="fas fa-home"></i>
      </div>
      <a href="{{ route('evacuation.index') }}" class="small-box-footer">
        Open centers <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box small-box-orange text-white">
      <div class="inner">
        <h3>{{ $stats['received_donations'] ?? 0 }}</h3>
        <p>Donations Received</p>
      </div>
      <div class="icon">
        <i class="fas fa-donate"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        View donations <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-purple text-white"><div class="inner"><h3>{{ $stats['total_centers'] ?? 0 }}</h3><p>Evacuation Centers</p></div><div class="icon"><i class="fas fa-building"></i></div></div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-yellow text-white"><div class="inner"><h3>{{ $stats['total_evacuees'] ?? 0 }}</h3><p>Active Evacuees</p></div><div class="icon"><i class="fas fa-users"></i></div></div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-brown text-white"><div class="inner"><h3>{{ $stats['inventory_total'] ?? 0 }}</h3><p>Active Inventory Items</p></div><div class="icon"><i class="fas fa-boxes"></i></div></div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-red text-white"><div class="inner"><h3>{{ $stats['donations_total'] ?? 0 }}</h3><p>Total Donations</p></div><div class="icon"><i class="fas fa-heart"></i></div></div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title dashboard-section-title dashboard-title-alerts"><i class="fas fa-bell mr-2"></i>Operational Alerts</h3>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            @if(($stats['active_ops'] ?? 0) > 0)
              <span><span class="badge badge-success mr-2">Active</span> {{ $stats['active_ops'] }} relief operation(s) in progress</span>
            @else
              <span><span class="badge badge-warning mr-2">Warning</span> No active relief operations</span>
            @endif
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><span class="badge badge-info mr-2">Info</span> System ready</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title dashboard-section-title dashboard-title-inventory"><i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Items</h3>
      </div>
      <div class="card-body">
        @php $low = $stats['low_stock_items'] ?? collect(); @endphp
        @if($low && count($low) > 0)
          <ul class="list-unstyled mb-0">
            @foreach($low as $i)
              <li>
                <strong>{{ $i->name ?? ($i->label ?? 'Item') }}</strong>
                <span class="text-muted"> — {{ $i->status ?? '' }}</span>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted">No low stock alerts at this time.</p>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Operational Metrics</h3></div>
  <div class="card-body">
    @php
      $metricValues = [
        'Occupancy' => (int) ($stats['occupancy_percent'] ?? 0),
        'Distributions' => (int) ($stats['total_distributions'] ?? 0),
        'Donations' => (int) ($stats['donations_total'] ?? 0),
        'Low-stock items' => count($stats['low_stock_items'] ?? []),
      ];
      $metricMax = max(1, max($metricValues));
    @endphp
    @foreach($metricValues as $label => $value)
      <div class="mb-3">
        <div class="d-flex justify-content-between small mb-1"><span>{{ $label }}</span><strong>{{ number_format($value) }}{{ $label === 'Occupancy' ? '%' : '' }}</strong></div>
        <div class="progress" style="height:10px;"><div class="progress-bar bg-danger" role="progressbar" style="width:{{ min(100, round(($value / $metricMax) * 100)) }}%" aria-label="{{ $label }}"></div></div>
      </div>
    @endforeach
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title dashboard-section-title dashboard-title-relief"><i class="fas fa-box-open mr-2"></i>Most-Needed Items</h3></div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          @forelse($stats['most_needed_items'] ?? [] as $needed)
            <li class="list-group-item d-flex justify-content-between"><span>{{ $needed->item?->name ?? 'Item' }}</span><strong>{{ number_format($needed->total_quantity) }} distributed</strong></li>
          @empty
            <li class="list-group-item text-muted">No distribution data yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card dashboard-map-card shadow-sm border-0">
      <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="card-title dashboard-section-title dashboard-title-map"><i class="fas fa-map-marker-alt"></i>Active Evacuation Centers — Map View</h3>
        <span class="badge badge-light">Live occupancy polling</span>
      </div>
      <div class="card-body p-0">
        <div id="evacuationMap" class="drms-internal-evac-map"></div>
        <div class="dashboard-map-legend px-3 py-2 border-top small text-muted">
          <span class="dashboard-map-legend__dot dashboard-map-legend__dot--open"></span> Open with slots
          <span class="dashboard-map-legend__dot dashboard-map-legend__dot--full ml-3"></span> Full
        </div>
        <div class="px-3 py-2 small text-muted">Map source: Leaflet with OpenStreetMap tiles. Occupancy refreshes from active check-ins every 30 seconds. Center coordinates come from the database; missing coordinates use configured municipality fallbacks.</div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title dashboard-section-title dashboard-title-activity"><i class="fas fa-history mr-2"></i>Recent Activity</h3></div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          @forelse($stats['recent_activity'] ?? [] as $activity)
            <li class="list-group-item d-flex justify-content-between"><span><strong>{{ ucfirst($activity->action) }}</strong> {{ $activity->record_label ?? $activity->module }}</span><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small></li>
          @empty
            <li class="list-group-item text-muted">No activity in this period.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
  .dashboard-section-title {
    font-weight: 600;
  }

  .dashboard-section-title i {
    width: 1.25rem;
    color: inherit !important;
    text-align: center;
  }

  body.drms-admin-theme .card-header .card-title.dashboard-title-alerts { color: #9b2c2c; }
  body.drms-admin-theme .card-header .card-title.dashboard-title-inventory { color: #b45309; }
  body.drms-admin-theme .card-header .card-title.dashboard-title-relief { color: #166534; }
  body.drms-admin-theme .card-header .card-title.dashboard-title-map { color: #0f766e; }
  body.drms-admin-theme .card-header .card-title.dashboard-title-activity { color: #1d4ed8; }
  .dashboard-map-card { border-radius: 10px; overflow: hidden; }
  .drms-internal-evac-map { height: 380px; min-height: 320px; width: 100%; }
  .dashboard-map-legend { background: #fff; }
  .dashboard-map-legend__dot { display: inline-block; width: 12px; height: 12px; border-radius: 50%; vertical-align: middle; margin-right: 4px; }
  .dashboard-map-legend__dot--open { background: #2e7d32; border: 2px solid #1b5e20; }
  .dashboard-map-legend__dot--full { background: #757575; border: 2px solid #424242; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  var map = L.map('evacuationMap').setView([14.5171, 121.2672], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  var markerLayer = L.layerGroup().addTo(map);

  function renderCenters(centers) {
    markerLayer.clearLayers();

    centers.forEach(function(c) {
      var markerStyle = c.status === 'full'
        ? { background: '#757575', border: '#424242' }
        : { background: '#2e7d32', border: '#1b5e20' };
      var marker = L.marker([c.latitude, c.longitude], {
        icon: L.divIcon({
          className: 'drms-internal-evac-marker-wrap',
          html: '<div class="drms-internal-evac-marker" style="background:' + markerStyle.background + ';border-color:' + markerStyle.border + '"><i class="fas fa-home"></i></div>',
          iconSize: [36, 36],
          iconAnchor: [18, 18],
          popupAnchor: [0, -18]
        })
      }).addTo(markerLayer);
      marker.bindPopup(
        '<strong>' + c.name + '</strong><br>' +
        'Status: ' + c.status + '<br>' +
        'Occupancy: ' + c.current_occupancy + ' / ' + c.capacity
      );
    });
  }

  function refreshMapOccupancy() {
    fetch('{{ route('public.evac_centers.map_data') }}?t=' + Date.now(), {
      headers: { 'Accept': 'application/json' },
      cache: 'no-store'
    })
      .then(function(response) { return response.ok ? response.json() : Promise.reject(response.status); })
      .then(function(payload) { renderCenters(Array.isArray(payload) ? payload : (payload.data || [])); })
      .catch(function() { /* Keep the last known map state when polling is unavailable. */ });
  }

  refreshMapOccupancy();
  window.setInterval(refreshMapOccupancy, 30000);
</script>
@endpush

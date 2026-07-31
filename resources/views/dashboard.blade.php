@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box small-box-red text-white">
      <div class="inner">
        <h3>{{ $activeOps }}</h3>
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
        <h3>{{ $totalDistributions }}</h3>
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
        <h3>{{ $occupancyPercent }}%</h3>
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
        <h3>{{ $totalDonations }}</h3>
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
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-bell mr-2"></i>Operational Alerts</h3>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><span class="badge badge-warning mr-2">Warning</span> No active relief operations</span>
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
        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Items</h3>
      </div>
      <div class="card-body">
        <p class="text-muted">No low stock alerts at this time.</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-map-marker-alt mr-2"></i>
          Active Evacuation Centers — Map View
        </h3>
      </div>
      <div class="card-body p-0">
        <div id="evacuationMap" style="height:400px;"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  var map = L.map('evacuationMap').setView([14.5171, 121.2672], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  @php
    $evacuationCenters = \App\Models\EvacuationCenter::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get(['name','latitude','longitude','status','current_occupancy','capacity']);
  @endphp

  var centers = @json($evacuationCenters);

  centers.forEach(function(c) {
    var color = c.status === 'full' ? 'red' : (c.status === 'closed' ? 'grey' : 'green');
    var marker = L.circleMarker([c.latitude, c.longitude], {
      color: color, fillColor: color, fillOpacity: 0.8, radius: 10
    }).addTo(map);
    marker.bindPopup(
      '<strong>' + c.name + '</strong><br>' +
      'Status: ' + c.status + '<br>' +
      'Occupancy: ' + c.current_occupancy + ' / ' + c.capacity
    );
  });
</script>
@endpush

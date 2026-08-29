@extends('layouts.app')

@section('title', 'Evacuation Centers')
@section('page-title', 'Evacuation Centers')
@section('breadcrumb')
  <li class="breadcrumb-item active">Evacuation centers</li>
@endsection

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 evacuation-toolbar">
    <p class="text-muted mb-0 evacuation-toolbar__summary">Monitor shelter capacity, occupancy, and locations across Municipality of Baras.</p>
    <div class="evacuation-toolbar__actions">
      <a href="/evac-centers" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt"></i> Public shelters
      </a>
      <button type="button" class="btn btn-sm btn-primary" id="btnAddEvac" title="Add center">
        <i class="fas fa-plus"></i> Add center
      </button>
    </div>
  </div>

  <div class="row" id="evacKpiRow">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3 id="evacKpiOpen">{{ $summary['active'] ?? $centers->where('status','active')->count() }}</h3>
          <p>Open centers</p>
        </div>
        <div class="icon"><i class="fas fa-door-open"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 id="evacKpiOcc">{{ $centers->sum('current_occupancy') }} <small>/ {{ $centers->sum('capacity') }}</small></h3>
          <p>Total occupancy</p>
        </div>
        <div class="icon"><i class="fas fa-users"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3 id="evacKpiFamilies">{{ $centers->sum('active_count') }}</h3>
          <p>Families registered</p>
        </div>
        <div class="icon"><i class="fas fa-people-arrows"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3 id="evacKpiMedical">0</h3>
          <p>Medical needs</p>
        </div>
        <div class="icon"><i class="fas fa-ambulance"></i></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-map mr-1"></i> Center locations</h3>
          <span class="badge badge-light ml-2">{{ $centers->count() }} on map</span>
        </div>
        <div class="card-body p-0">
          <div id="drmsEvacAdminMap" class="drms-evac-admin-map" style="height:360px;"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Overall occupancy</h3></div>
        <div class="card-body text-center">
          @php
            $totalCapacity = $centers->sum('capacity');
            $totalOccupancy = $centers->sum('current_occupancy');
            $totalPct = $totalCapacity ? (int) round(($totalOccupancy / $totalCapacity) * 100) : 0;
          @endphp
          <div class="drms-evac-gauge" style="--pct: {{ $totalPct }}"><span>{{ $totalPct }}%</span></div>
          <p class="small text-muted mb-0 mt-2">{{ $totalOccupancy }} of {{ $totalCapacity }} slots used</p>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3 class="card-title">Status mix</h3></div>
        <div class="card-body">
          <div class="drms-chart-wrap"><canvas id="chartEvacStatus"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Occupancy by center (%)</h3></div>
        <div class="card-body">
          <div class="drms-chart-wrap drms-chart-wrap--medium"><canvas id="chartEvacOcc"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row drms-evac-cards mb-3" id="drmsEvacCardsWrap">
    @foreach($centers as $center)
      <div class="col-lg-4 col-md-6">
        <div class="card drms-evac-center-card h-100" data-evac-id="{{ $center->id }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="mb-0">{{ $center->name }}</h5>
              <span class="badge badge-{{ $center->status === 'open' ? 'success' : ($center->status === 'full' ? 'danger' : 'secondary') }}">{{ ucfirst($center->status) }}</span>
            </div>
            <div class="small text-muted mb-2">{{ $center->barangay }}</div>
            <div class="d-flex justify-content-between small mb-1">
              <span>Occupancy</span>
              <strong>{{ $center->current_occupancy }} / {{ $center->capacity }}</strong>
            </div>
            <div class="progress progress-sm mb-2">
              @php $pct = $center->capacity ? round(($center->current_occupancy / $center->capacity) * 100) : 0; @endphp
              <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success') }}" style="width: {{ min(100, $pct) }}%"></div>
            </div>
            <div class="small text-muted">
              {{ $center->active_count ?? 0 }} families ·
              {{ max(0, $center->capacity - $center->current_occupancy) }} slots open ·
              0 medical
            </div>
          </div>
          <div class="card-footer py-2 bg-white border-top-0">
            <button type="button" class="btn btn-sm btn-outline-primary btn-evac-families" data-id="{{ $center->id }}" data-name="{{ $center->name }}">
              <i class="fas fa-users"></i> View families
            </button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">All centers</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" id="btnRefreshEvacTable" title="Refresh table">
          <i class="fas fa-sync-alt"></i>
        </button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table id="tblEvac" class="table table-bordered table-striped table-hover w-100">
        <thead>
          <tr><th>Name</th><th>Barangay</th><th>Occupancy</th><th>Status</th><th>Actions</th></tr>
        </thead>
      </table>
    </div>
  </div>

  {{-- Modals (same IDs used by DRMS.bindApiCrud and handlers) --}}
  <div class="modal fade" id="evacModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <form id="formEvac" class="modal-content">
        @csrf
        <input type="hidden" id="crudRecordId" value="">
        <input type="hidden" name="_method" id="crudFormMethod" value="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="evacModalTitle">Add evacuation center</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small mb-3">Fields marked <span class="text-danger">*</span> are required. Use the sections below so staff and evacuees know who to call and what families must bring.</p>

          <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3"><i class="fas fa-building mr-1"></i> Center identity</h6>
          <div class="form-group">
            <label for="evacName">Shelter / facility name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="evacName" class="form-control" required placeholder="e.g. San Jose Elementary School — Gymnasium">
            <small class="form-text text-muted">Official name shown on the public evacuation map and reports.</small>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="evacBarangay">Barangay</label>
              <select name="barangay" id="evacBarangay" class="form-control" required>
                <option value="">— Select barangay —</option>
                <option value="San Jose">San Jose</option>
                <option value="San Juan">San Juan</option>
              </select>
              <small class="form-text text-muted">Used for map pin and filtering.</small>
            </div>
            <div class="col-md-6 form-group">
              <label for="evacStatus">Shelter status</label>
              <select name="status" id="evacStatus" class="form-control">
                <option value="open">Open — accepting evacuees</option>
                <option value="full">Full — no new families</option>
                <option value="closed">Closed — not operating</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="evacAddress">Full street address</label>
            <textarea name="address" id="evacAddress" class="form-control" rows="2" required placeholder="Street, landmark, directions for drivers"></textarea>
            <small class="form-text text-muted">Physical location of the building entrance families should use.</small>
          </div>

          <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-users mr-1"></i> Capacity & census <small class="font-weight-normal">(staff-maintained)</small></h6>
          <p class="small text-muted">These numbers are <strong>operational counts for this shelter</strong>, not resident phone numbers. Update them as families check in or leave.</p>
          <div class="row">
            <div class="col-md-6 col-lg-3 form-group">
              <label for="evacCapacity">Maximum capacity</label>
              <input type="number" name="capacity" id="evacCapacity" class="form-control" value="1" min="1" required>
              <small class="form-text text-muted">Total persons (beds/mats) this center can hold.</small>
            </div>
            <div class="col-md-6 col-lg-3 form-group">
              <label for="evacOccupancy">Current headcount</label>
              <input type="number" name="current_occupancy" id="evacOccupancy" class="form-control" value="0" min="0" required>
              <small class="form-text text-muted">People physically inside the shelter now.</small>
            </div>
            <div class="col-md-6 col-lg-3 form-group">
              <label for="evacFamilies">Families registered</label>
              <input type="number" name="families_registered" id="evacFamilies" class="form-control" value="0" min="0">
              <small class="form-text text-muted">Pre-registered families (online or at desk), including not yet checked in.</small>
            </div>
            <div class="col-md-6 col-lg-3 form-group">
              <label for="evacMedical">Medical needs flagged</label>
              <input type="number" name="medical_needs_count" id="evacMedical" class="form-control" value="0" min="0">
              <small class="form-text text-muted">Families/members needing medical attention at this center.</small>
            </div>
          </div>

          <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-phone mr-1"></i> Center contacts <small class="font-weight-normal">(not evacuee numbers)</small></h6>
          <div class="row">
            <div class="col-md-6 form-group">
              <label for="evacContactPerson">On-duty manager / focal person</label>
              <input type="text" name="contact_person" id="evacContactPerson" class="form-control" placeholder="e.g. Brgy. Captain, Evac Center Manager, MDRRMO duty officer">
              <small class="form-text text-muted">Name of the staff member or official responsible for this shelter shift.</small>
            </div>
            <div class="col-md-6 form-group">
              <label for="evacContactPhone">Center hotline</label>
              <input type="text" name="contact_phone" id="evacContactPhone" class="form-control" placeholder="e.g. 0917-123-4567">
              <small class="form-text text-muted">Phone for evacuees and responders to ask about slots, directions, or emergencies at <em>this</em> center — not a resident’s personal mobile.</small>
            </div>
          </div>

          <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-clipboard-list mr-1"></i> Family intake — procedure &amp; requirements</h6>
          <p class="small text-muted mb-2">Shown to families on the public evacuation page when they select this center. Explain the steps to be allowed inside and what documents to bring.</p>
          <div class="form-group">
            <label for="evacIntakeProcedures">Check-in procedure for families</label>
            <textarea name="intake_procedures" id="evacIntakeProcedures" class="form-control" rows="6" placeholder="1. Go only if status is OPEN and slots are available.&#10;2. Report to the registration desk with family head name and member count.&#10;3. Present valid government ID for the family head (and adults if requested).&#10;4. Declare medical needs, pregnancy, infants, and PWDs.&#10;5. Receive your FAM- QR token and keep it for check-in.&#10;6. Wait for staff room/bay assignment; follow center rules."></textarea>
            <small class="form-text text-muted">Step-by-step instructions from arrival to assignment inside the shelter.</small>
          </div>
          <div class="form-group mb-0">
            <label for="evacRequiredItems">Required documents &amp; items to bring</label>
            <textarea name="required_items" id="evacRequiredItems" class="form-control" rows="4" placeholder="Valid ID (family head), FAM QR token after registration, clothes, hygiene kit, prescribed medicines, infant supplies, drinking water, important documents in a sealed bag."></textarea>
            <small class="form-text text-muted">IDs, tokens, and belongings families must have to be admitted.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save center</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="evacFamiliesModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-0" id="evacFamiliesModalTitle">Registered families</h5>
            <p class="small text-muted mb-0 mt-1" id="evacFamiliesModalMeta"></p>
          </div>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0">
          <div id="evacFamiliesLoading" class="text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
            <div>Loading families…</div>
          </div>
          <div id="evacFamiliesEmpty" class="text-center py-5 text-muted d-none">
            <i class="fas fa-users-slash fa-2x mb-2"></i>
            <div>No families registered at this center yet.</div>
            <p class="small mb-0">Families can register on the <a href="{{ route('public.home') }}" target="_blank" rel="noopener">public evacuation page</a>.</p>
          </div>
          <div class="table-responsive d-none" id="evacFamiliesTableWrap">
            <table class="table table-striped table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th>Family head</th>
                  <th>Members</th>
                  <th>Contact</th>
                  <th>Medical</th>
                  <th>Token</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="evacFamiliesTableBody"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshEvacFamilies"><i class="fas fa-sync-alt"></i> Refresh</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="evacFamilyDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="evacFamilyDetailTitle">Family details</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body" id="evacFamilyDetailBody"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning d-none" id="btnEvacFamilyCheckout"><i class="fas fa-sign-out-alt"></i> Check out family</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('styles')
  <style>
    #evacKpiRow .small-box {
      overflow: hidden !important;
    }

    #evacKpiRow .small-box .icon {
      top: 10px !important;
      right: 12px !important;
      width: 64px;
      height: 64px;
      display: flex !important;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }

    #evacKpiRow .small-box .icon i {
      font-size: 58px !important;
      line-height: 1;
    }

    .evacuation-toolbar {
      gap: .5rem;
    }

    .evacuation-toolbar__summary {
      flex: 1 1 30rem;
      min-width: 0;
    }

    .evacuation-toolbar__actions {
      display: flex;
      flex: 0 1 auto;
      flex-wrap: wrap;
      gap: .5rem;
      justify-content: flex-end;
      max-width: 100%;
    }

    @media (max-width: 575.98px) {
      #evacKpiRow .small-box .icon {
        display: none !important;
      }

      .evacuation-toolbar__actions {
        width: 100%;
        justify-content: flex-start;
      }
    }

  </style>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

  <script>
    var mapCenters = {!! json_encode($centers->map(function($c){ return [
        'id' => $c->id,
        'name' => $c->name,
        'barangay' => $c->barangay,
        'address' => $c->address,
        'capacity' => (int) ($c->capacity ?? 0),
        'current_occupancy' => (int) ($c->current_occupancy ?? 0),
        'available_slots' => max(0, ($c->capacity ?? 0) - ($c->current_occupancy ?? 0)),
        'status' => $c->status,
        'families_registered' => (int) ($c->families_registered ?? 0),
        'medical_needs_count' => (int) ($c->medical_needs_count ?? 0),
        'contact_person' => $c->contact_person,
        'contact_phone' => $c->contact_phone,
        'notes' => $c->notes,
        'latitude' => $c->latitude ? (float)$c->latitude : null,
        'longitude' => $c->longitude ? (float)$c->longitude : null,
        'marker' => [
          'icon' => 'fa-home',
          'marker_bg' => ($c->status === 'full' ? '#c62828' : ($c->status === 'open' ? '#2e7d32' : '#6c757d')),
          'border_color' => ($c->status === 'full' ? '#8e0000' : ($c->status === 'open' ? '#1b5e20' : '#545454')),
        ],
    ]; })->values()) !!};

    $(function () {
      var csrfToken = '{{ csrf_token() }}';
      var externalEndpoint = 'https://drvms.freedev.app/api/v1/public/evacuation-centers?limit=100';
      var localEndpoint = '{{ url('/api/v1/public/evacuation-centers') }}?limit=100';
      var table = null;
      var redrawEvacMap = null;

      function fetchJson(url) {
        return fetch(url, {
          headers: { 'Accept': 'application/json' }
        }).then(function (response) {
          if (!response.ok) {
            throw new Error('Request failed: ' + response.status);
          }
          return response.json();
        });
      }

      function initializeTable(centers) {
        if (table) {
          table.destroy();
        }
        table = $('#tblEvac').DataTable({
          data: centers,
          columns: [
            { data: 'name' },
            { data: 'barangay', defaultContent: '—' },
            { data: null, orderable: false, render: function (row) {
                var cap = parseInt(row.capacity, 10) || 0;
                var occ = parseInt(row.current_occupancy, 10) || 0;
                var pct = cap > 0 ? Math.round((occ / cap) * 100) : 0;
                var bar = pct >= 90 ? 'bg-danger' : (pct >= 70 ? 'bg-warning' : 'bg-success');
                return '<div class="small">' + occ + ' / ' + cap + ' (' + pct + '%)</div>'
                  + '<div class="progress progress-xs"><div class="progress-bar ' + bar + '" style="width:' + Math.min(100, pct) + '%"></div></div>';
            }},
            { data: 'status', render: function (s) {
                var c = { active: 'success', open: 'success', full: 'danger', closed: 'secondary' };
                return '<span class="badge badge-' + (c[s] || 'light') + '">' + (s || '') + '</span>';
            }},
            { data: null, orderable: false, searchable: false, render: function (row) {
                return '<div class="btn-group btn-group-xs">'
                  + '<button type="button" class="btn btn-primary btn-sm btn-evac-families" data-id="' + row.id + '" data-name="' + escHtml(row.name || '') + '" title="Families"><i class="fas fa-users"></i></button>'
                  + '<button type="button" class="btn btn-info btn-sm btn-api-edit" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>'
                  + '<button type="button" class="btn btn-danger btn-sm btn-api-delete" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>'
                  + '</div>';
            }}
          ],
          order: [[0, 'asc']],
          processing: true,
          language: {
            emptyTable: 'No evacuation centers found',
            loadingRecords: 'Loading centers…'
          }
        });
      }

      function applyCenters(centers) {
        mapCenters = centers;
        initializeTable(centers);
        if (redrawEvacMap) {
          redrawEvacMap();
        }
      }

      function loadCentersFromApi() {
        fetchJson(externalEndpoint)
          .then(function (payload) {
            var externalCenters = Array.isArray(payload && payload.data) ? payload.data : [];
            return fetchJson(localEndpoint).then(function (localPayload) {
              var localCenters = Array.isArray(localPayload && localPayload.data) ? localPayload.data : [];
              var merged = {};
              localCenters.concat(externalCenters).forEach(function (center) {
                if (center && center.id !== undefined) {
                  merged[center.id] = center;
                }
              });
              applyCenters(Object.keys(merged).map(function (id) { return merged[id]; }));
            });
          })
          .catch(function () {
            return fetchJson(localEndpoint)
              .then(function (payload) {
                var centers = Array.isArray(payload && payload.data) ? payload.data : [];
                applyCenters(centers);
              })
              .catch(function () {
                applyCenters([]);
              });
          });
      }

      loadCentersFromApi();

      var activeFamiliesCenterId = 0;
      var activeFamiliesCenterName = '';
      var cachedFamilies = [];

      function escHtml(s) { return $('<div>').text(s == null ? '' : String(s)).html(); }

      function renderFamilyDetail(f) {
        var medical = (f.needs || '').trim();
        var html = '<dl class="row mb-0">';
        html += '<dt class="col-sm-4">Family head</dt><dd class="col-sm-8"><strong>' + escHtml(f.name) + '</strong></dd>';
        html += '<dt class="col-sm-4">Members</dt><dd class="col-sm-8">' + escHtml(f.family_members) + '</dd>';
        html += '<dt class="col-sm-4">Barangay origin</dt><dd class="col-sm-8">' + (f.barangay_origin ? escHtml(f.barangay_origin) : '<span class="text-muted">—</span>') + '</dd>';
        html += '<dt class="col-sm-4">Medical needs</dt><dd class="col-sm-8">' + (medical ? '<span class="text-danger">' + escHtml(medical) + '</span>' : '<span class="text-muted">None declared</span>') + '</dd>';
        html += '<dt class="col-sm-4">ID presented</dt><dd class="col-sm-8">' + (f.id_presented ? escHtml(f.id_presented) : '<span class="text-muted">—</span>') + '</dd>';
        html += '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + (f.status === 'checked_in' ? '<span class="badge badge-success">Checked in</span>' : f.status === 'checked_out' ? '<span class="badge badge-secondary">Checked out</span>' : '<span class="badge badge-warning">' + escHtml(f.status) + '</span>') + '</dd>';
        html += '</dl>';
        return html;
      }

      function showFamilyDetail(familyId) {
        var family = cachedFamilies.find(function (row) { return String(row.id) === String(familyId); });
        if (!family) {
          return;
        }
        $('#evacFamilyDetailTitle').text('Family — ' + (family.name || 'Details'));
        $('#evacFamilyDetailBody').html(renderFamilyDetail(family));
        if (family.status === 'checked_in') {
          $('#btnEvacFamilyCheckout').removeClass('d-none')
            .data('family-id', family.id)
            .data('family-name', family.name || '');
        } else {
          $('#btnEvacFamilyCheckout').addClass('d-none').removeData('family-id').removeData('family-name');
        }
        $('#evacFamilyDetailModal').modal('show');
      }

      function renderFamiliesTable(families) {
        var $body = $('#evacFamiliesTableBody').empty();
        families.forEach(function (f) {
          var statusLabel = f.status === 'checked_in' ? '<span class="badge badge-success">Checked in</span>'
            : f.status === 'checked_out' ? '<span class="badge badge-secondary">Checked out</span>'
            : '<span class="badge badge-warning">' + escHtml(f.status) + '</span>';
          var row = '<tr' + (f.status === 'checked_out' ? ' class="text-muted"' : '') + '>'
            + '<td><strong>' + escHtml(f.name) + '</strong></td>'
            + '<td>' + escHtml(f.family_members) + '</td>'
            + '<td>' + (f.barangay_origin ? escHtml(f.barangay_origin) : '<span class="text-muted">—</span>') + '</td>'
            + '<td>' + (f.needs ? escHtml(f.needs) : '<span class="text-muted">—</span>') + '</td>'
            + '<td>' + (f.id_presented ? escHtml(f.id_presented) : '<span class="text-muted">—</span>') + '</td>'
            + '<td>' + statusLabel + '</td>'
            + '<td class="text-nowrap"><button type="button" class="btn btn-xs btn-outline-info btn-evac-family-detail" data-family-id="' + escHtml(f.id) + '">Details</button></td>'
            + '</tr>';
          $body.append(row);
        });
      }

      function resetEvacForm() {
        $('#formEvac')[0].reset();
        $('#crudRecordId').val('');
        $('#crudFormMethod').val('POST');
        $('#evacModalTitle').text('Add evacuation center');
      }

      function loadEditEvacForm(centerId) {
        var center = mapCenters.find(function (row) { return Number(row.id) === Number(centerId); });
        if (!center) {
          return;
        }

        $('#crudRecordId').val(center.id);
        $('#crudFormMethod').val('PUT');
        $('#evacModalTitle').text('Edit evacuation center');
        $('#evacName').val(center.name || '');
        $('#evacBarangay').val(center.barangay || '');
        $('#evacStatus').val(center.status || 'open');
        $('#evacAddress').val(center.address || '');
        $('#evacCapacity').val(center.capacity || 0);
        $('#evacOccupancy').val(center.current_occupancy || 0);
        $('#evacFamilies').val(center.families_registered || 0);
        $('#evacMedical').val(center.medical_needs_count || 0);
        $('#evacContactPerson').val(center.contact_person || '');
        $('#evacContactPhone').val(center.contact_phone || '');
        $('#evacIntakeProcedures').val(center.intake_procedures || '');
        $('#evacRequiredItems').val(center.required_items || '');
      }

      function loadFamiliesModal(centerId, centerName) {
        activeFamiliesCenterId = centerId;
        activeFamiliesCenterName = centerName || 'Center';
        $('#evacFamiliesModalTitle').text('Families — ' + activeFamiliesCenterName);
        $('#evacFamiliesModalMeta').text('');
        $('#evacFamiliesLoading').removeClass('d-none');
        $('#evacFamiliesEmpty').addClass('d-none');
        $('#evacFamiliesTableWrap').addClass('d-none');
        $('#evacFamiliesModal').modal('show');

        $.getJSON('/evacuation/' + centerId + '/evacuees')
          .done(function (res) {
            $('#evacFamiliesLoading').addClass('d-none');
            cachedFamilies = (res && res.data) ? res.data : [];
            var total = (res && typeof res.total !== 'undefined') ? res.total : cachedFamilies.length;
            var checkedIn = cachedFamilies.filter(function (f) { return f.status === 'checked_in'; }).length;
            $('#evacFamiliesModalMeta').text(total + ' total · ' + checkedIn + ' inside');
            if (!cachedFamilies.length) {
              $('#evacFamiliesEmpty').removeClass('d-none');
              $('#evacFamiliesTableWrap').addClass('d-none');
              return;
            }
            renderFamiliesTable(cachedFamilies);
            $('#evacFamiliesTableWrap').removeClass('d-none');
          })
          .fail(function () {
            $('#evacFamiliesLoading').addClass('d-none');
            $('#evacFamiliesEmpty').removeClass('d-none');
          });
      }

      $(document).on('click', '.btn-evac-families', function () {
        loadFamiliesModal($(this).data('id'), $(this).data('name') || '');
      });

      $(document).on('click', '.btn-evac-family-detail', function () {
        showFamilyDetail($(this).data('family-id'));
      });

      $('#btnRefreshEvacFamilies').on('click', function () {
        if (activeFamiliesCenterId) {
          loadFamiliesModal(activeFamiliesCenterId, activeFamiliesCenterName);
        }
      });

      $('#btnAddEvac').on('click', function () {
        resetEvacForm();
        $('#evacModal').modal('show');
      });

      $('#formEvac').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this);
        var recordId = $('#crudRecordId').val();
        var url = recordId ? '/evacuation/' + recordId : '{{ route('evacuation.store') }}';
        var data = $form.serialize();

        $.ajax({
          url: url,
          method: 'POST',
          data: data
        }).done(function () {
          window.location.reload();
        }).fail(function () {
          alert('Unable to save evacuation center. Please check your input and try again.');
        });
      });

      $('#btnRefreshEvacTable').on('click', function () {
        table.clear().rows.add(mapCenters).draw();
      });

      $(document).on('click', '.btn-api-edit', function () {
        var id = $(this).data('id');
        if (!id) {
          return;
        }
        resetEvacForm();
        loadEditEvacForm(id);
        $('#evacModal').modal('show');
      });

      $(document).on('click', '.btn-api-delete', function () {
        var id = $(this).data('id');
        if (!id) {
          return;
        }
        if (!confirm('Delete this evacuation center? This cannot be undone.')) {
          return;
        }
        $.ajax({
          url: '/evacuation/' + id,
          method: 'POST',
          data: { _method: 'DELETE', _token: csrfToken }
        }).done(function () {
          window.location.reload();
        }).fail(function () {
          alert('Unable to delete evacuation center.');
        });
      });

      $(document).on('click', '.btn-evac-family-detail', function () {
        showFamilyDetail($(this).data('family-id'));
      });

      $('#btnEvacFamilyCheckout').on('click', function () {
        var familyId = $(this).data('family-id');
        var familyName = $(this).data('family-name') || '';
        if (!familyId) {
          return;
        }
        if (!confirm('Check out this family? This will free their slots.')) {
          return;
        }
        $.ajax({
          url: '/evacuation/' + activeFamiliesCenterId + '/checkout/' + familyId,
          method: 'PATCH'
        }).done(function () {
          $('#evacFamilyDetailModal').modal('hide');
          loadFamiliesModal(activeFamiliesCenterId, activeFamiliesCenterName);
        }).fail(function () {
          alert('Failed to check out the family.');
        });
      });

      if (typeof Chart !== 'undefined') {
        var statusCounts = { active: 0, open: 0, full: 0, closed: 0 };
        mapCenters.forEach(function (c) {
          if (!c || !c.status) return;
          var normalized = c.status === 'active' ? 'open' : c.status;
          statusCounts[normalized] = (statusCounts[normalized] || 0) + 1;
        });

        new Chart(document.getElementById('chartEvacStatus').getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Active','Full','Closed'],
            datasets: [{ data: [statusCounts.open, statusCounts.full, statusCounts.closed], backgroundColor: ['#28a745', '#dc3545', '#6c757d'] }]
          },
          options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
        });

        var occLabels = [];
        var occValues = [];
        mapCenters.forEach(function (c) {
          if (!c) return;
          occLabels.push(c.name || 'Center');
          occValues.push(Math.min(100, c.capacity > 0 ? Math.round((c.current_occupancy / c.capacity) * 100) : 0));
        });

        new Chart(document.getElementById('chartEvacOcc').getContext('2d'), {
          type: 'bar',
          data: {
            labels: occLabels,
            datasets: [{ data: occValues, backgroundColor: '#1565c0' }]
          },
          options: {
            responsive:true,
            maintainAspectRatio:false,
            plugins:{ legend:{ display:false } },
            scales:{ x: { beginAtZero:true, max:100 }, y: { beginAtZero:true, max:100 } }
          }
        });
      }

      if (typeof L !== 'undefined' && document.getElementById('drmsEvacAdminMap')) {
        var map = L.map('drmsEvacAdminMap', { minZoom: 11, maxZoom: 17 }).setView([14.52, 121.27], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap' }).addTo(map);

        var bounds = [];
        var markers = {};
        redrawEvacMap = function () {
          Object.keys(markers).forEach(function (id) {
            map.removeLayer(markers[id]);
          });
          markers = {};
          bounds = [];
          var coordinateUse = {};
          if (Array.isArray(mapCenters)) {
            mapCenters.forEach(function (c) {
            if (!c) return;
            var latitude = parseFloat(c.latitude);
            var longitude = parseFloat(c.longitude);
            var hasCoordinates = Number.isFinite(latitude) && Number.isFinite(longitude);
            if (!hasCoordinates) {
              latitude = 14.52;
              longitude = 121.27;
            }
            var coordinateKey = latitude.toFixed(5) + ',' + longitude.toFixed(5);
            var duplicateIndex = coordinateUse[coordinateKey] || 0;
            coordinateUse[coordinateKey] = duplicateIndex + 1;
            if (duplicateIndex > 0) {
              var offset = duplicateIndex * 0.00035;
              latitude += offset;
              longitude += offset;
            }
            var m = c.marker || {};
            var icon = L.divIcon({
              className: 'drms-evac-marker-wrap',
              html: '<div class="drms-evac-marker" style="background:' + (m.marker_bg || '#2e7d32') + ';border-color:' + (m.border_color || '#1b5e20') + '"><i class="fas ' + (m.icon || 'fa-home') + '"></i></div>',
              iconSize: [30, 30],
              iconAnchor: [15, 15]
            });
            var locationNote = hasCoordinates ? '' : '<br><small>Exact location not provided</small>';
            var mk = L.marker([latitude, longitude], { icon: icon })
              .bindPopup('<strong>' + (c.name || '') + '</strong><br>' + (c.current_occupancy || 0) + ' / ' + (c.capacity || 0) + '<br>' + (c.available_slots || 0) + ' slots open' + locationNote)
              .addTo(map);
            markers[c.id] = mk;
            bounds.push([latitude, longitude]);
            });
          }

          if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
          else if (bounds.length === 1) map.setView(bounds[0], 14);
        };
        redrawEvacMap();

        $('.drms-evac-center-card').on('click', function () {
          var id = parseInt($(this).data('evac-id'), 10);
          if (markers[id]) {
            map.setView(markers[id].getLatLng(), Math.max(map.getZoom(), 14));
            markers[id].openPopup();
          }
        });

        setTimeout(function () { map.invalidateSize(); }, 300);
      }
    });
  </script>
@endpush


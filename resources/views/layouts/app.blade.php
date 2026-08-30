<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') | DRMS</title>
  <link rel="icon" type="image/png" href="/assets/logo/baras_seal_l.png">

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- OverlayScrollbars (prevents double scrollbars and improves sidebar scrolling) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css">
  <!-- AdminLTE v3 -->
  <link rel="stylesheet" href="/assets/adminlte/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <!-- Group 1 DRMS admin overrides (match visuals exactly) -->
  <link rel="stylesheet" href="https://drvms.freedev.app/assets/css/drms-admin.css">
  <link rel="stylesheet" href="/assets/css/drms-admin.css">

  <style>
    /* Ensure modals and backdrops show correctly without changing the theme visuals */
    body.drms-admin-theme .modal,
    body.drms-admin-theme .modal.fade.show,
    body.drms-admin-theme .modal.show {
      opacity: 1 !important;
      visibility: visible !important;
    }

    body.drms-admin-theme .modal-dialog,
    body.drms-admin-theme .modal-content {
      pointer-events: auto !important;
      z-index: 1060 !important;
    }

    body.drms-admin-theme .modal-backdrop,
    body.drms-admin-theme .modal-backdrop.show {
      z-index: 1040 !important;
      opacity: 0.5 !important;
      visibility: visible !important;
    }

    /* Keep overlayScrollbars content stacking above the backdrop so fixed modals render correctly */
    body.drms-admin-theme .os-padding {
      z-index: 1050 !important;
    }

    /* Restore DRMS admin modal header and buttons */
    body.drms-admin-theme .modal .modal-header {
      background: linear-gradient(135deg, var(--drms-burgundy-dark, #3d1419) 0%, var(--drms-burgundy, #6d1f2a) 42%, var(--drms-red, #c62828) 100%) !important;
      color: #fff !important;
      border-bottom: none !important;
      padding: 1rem 1.25rem !important;
      align-items: center !important;
      position: relative !important;
    }

    body.drms-admin-theme .modal .modal-title {
      color: #fff !important;
    }

    body.drms-admin-theme .modal .close {
      color: #fff !important;
      opacity: 1 !important;
    }

    body.drms-admin-theme .modal .btn-primary {
      background-color: var(--drms-red, #c62828) !important;
      border-color: var(--drms-red-hover, #b71c1c) !important;
      color: #fff !important;
    }

    body.drms-admin-theme .modal .btn-primary:hover,
    body.drms-admin-theme .modal .btn-primary:focus {
      background-color: var(--drms-red-hover, #b71c1c) !important;
      border-color: var(--drms-red-hover, #b71c1c) !important;
    }

    body.drms-admin-theme .modal .btn-secondary {
      background-color: #fff !important;
      border-color: rgba(0,0,0,0.15) !important;
      color: #4a3032 !important;
    }

    body.drms-admin-theme .modal .btn-secondary:hover,
    body.drms-admin-theme .modal .btn-secondary:focus {
      background-color: #f8f9fa !important;
    }

    /* Slightly lighter hover/active state to give subtle depth */
    .sidebar-dark-danger .nav-sidebar > .nav-item > .nav-link:hover {
      background-color: rgba(255,255,255,0.03) !important;
    }

    .nav-sidebar .nav-link,
    .nav-sidebar .nav-header {
      color: #fff !important;
    }

    .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.75) !important; }
    .nav-sidebar .nav-link.active .nav-icon { color: #fff !important; }
    .nav-sidebar .nav-header { color: rgba(255,255,255,0.5) !important; }

    /* Keep the canonical AdminLTE user-panel sizing and spacing intact; color-only tweaks live in drms-admin.css */

    /* ── Small box card colors ── */
    .small-box.small-box-red    { background-color: #C0392B !important; }
    .small-box.small-box-brown  { background-color: #5D4037 !important; }
    .small-box.small-box-green  { background-color: #1E8449 !important; }
    .small-box.small-box-green2 { background-color: #1B5E20 !important; }
    .small-box.small-box-orange { background-color: #D35400 !important; }
    .small-box.small-box-yellow { background-color: #D4AC0D !important; }
    .small-box.small-box-purple { background-color: #4A235A !important; }

    /* Ensure icon and footer inherit correctly */
    .small-box { position: relative !important; overflow: visible !important; }
    .small-box .small-box-footer {
      background-color: rgba(0,0,0,0.1) !important;
      color: rgba(255,255,255,0.85) !important;
      display: block;
      padding: 3px 0;
      text-align: center;
      text-decoration: none;
      font-size: 13px;
    }
    .small-box .small-box-footer:hover {
      background-color: rgba(0,0,0,0.2) !important;
      color: #fff !important;
    }
    .small-box .icon { 
      position: absolute !important; 
      right: 15px !important; 
      top: 15px !important;
      color: rgba(0,0,0,0.15) !important; 
      z-index: 1 !important;
      display: block !important;
      opacity: 1 !important;
    }
    .small-box .icon i { 
      font-size: 70px !important; 
      color: rgba(0,0,0,0.15) !important;
    }
    .small-box.bg-success .icon i { 
      color: rgba(255,255,255,0.8) !important; 
    }
    .small-box h3 { font-size: 2.2rem !important; color: #fff !important; }
    .small-box p   { color: rgba(255,255,255,0.9) !important; }

    @media (max-width: 575.98px) {
      .small-box .icon {
        display: none !important;
      }
    }

    /* ── Content header ── */
    .content-header h1 { font-size: 1.5rem; font-weight: 600; }

    /* ---- Top navbar ---- */
    body.drms-admin-theme .main-header.navbar {
      border-bottom: 2px solid var(--drms-navbar-border) !important;
      background: linear-gradient(180deg, #fff 0%, #fdf8f8 100%) !important;
    }

    body.drms-admin-theme .main-header .nav-link {
      color: #4a3032 !important;
    }

    body.drms-admin-theme .main-header .nav-link:hover {
      color: var(--drms-red) !important;
    }

    body.drms-admin-theme .navbar-light .navbar-nav .nav-link:focus,
    body.drms-admin-theme .navbar-light .navbar-nav .nav-link:hover {
      color: var(--drms-red) !important;
    }

    /* Prevent debug tools (Kint/Debugbar) from creating very wide elements that cause
       a global horizontal scrollbar. Keep these rules scoped and non-invasive. */
    .kint-rich, .kint-dump, .kint, .kint pre, .kint table, .phpdebugbar * {
      max-width: 100% !important;
      overflow-x: auto !important;
      white-space: normal !important;
      word-break: break-word !important;
    }
      body.drms-admin-theme .brand-link .drms-municipal-seal {
        object-fit: contain !important;
        opacity: 1 !important;
      }
  </style>

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed drms-admin-theme">
<div class="wrapper">

  @php $profileUrl = \Illuminate\Support\Facades\Route::has('profile') ? route('profile') : '#'; @endphp

  {{-- ═══ NAVBAR ═══ --}}
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('public.home') }}" class="nav-link" target="_blank">
          <i class="fas fa-home mr-1"></i>
          Public Site
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      @php
        $currentUser = auth()->user();
        $currentUserId = $currentUser?->id;
        $currentRole = $currentUser?->role?->slug;
        $notificationService = app(\App\Services\NotificationService::class);
        $bellCount = $notificationService->unreadCountForUser($currentUserId, $currentRole);
        $recentNotifs = $notificationService->recentForUser($currentUserId, $currentRole, 5);
      @endphp
      <li class="nav-item dropdown" id="navNotifDropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="navNotifCount" style="display: {{ $bellCount > 0 ? 'inline-flex' : 'none' }};">{{ $bellCount }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="navNotifMenu">
          <span class="dropdown-item dropdown-header" id="navNotifHeader">Notifications</span>
          <div class="dropdown-divider"></div>
          <div id="navNotifItems">
            @forelse($recentNotifs as $n)
              @php
                $iconMap = [
                  'low_stock'             => 'fas fa-exclamation-triangle text-warning',
                  'center_full'           => 'fas fa-home text-danger',
                  'new_donation'          => 'fas fa-heart text-success',
                  'distribution_recorded' => 'fas fa-truck text-primary',
                  'general'               => 'far fa-bell text-muted',
                ];
                $icon = $iconMap[$n->type] ?? 'far fa-bell text-muted';
              @endphp
                <a href="{{ $n->link ?? route('notifications.index') }}"
                  class="dropdown-item nav-notification-link"
                  data-read-url="{{ route('notifications.read', $n->id) }}">
                <i class="{{ $icon }} mr-2"></i>
                {{ \Illuminate\Support\Str::limit($n->title, 35) }}
                <span class="float-right text-muted text-sm">
                  {{ $n->created_at->diffForHumans() }}
                </span>
              </a>
              <div class="dropdown-divider"></div>
            @empty
              <span class="dropdown-item text-muted">Loading…</span>
              <div class="dropdown-divider"></div>
            @endforelse
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ $profileUrl }}" title="Profile">
          <img src="https://drvms.freedev.app/assets/adminlte/dist/img/user2-160x160.jpg"
               alt=""
               class="img-circle mr-1"
               style="width:28px;height:28px;object-fit:cover;">
          <span class="d-none d-md-inline">{{ Auth::user()->role->slug === 'super_admin' ? 'admin' : (in_array(Auth::user()->role->slug, ['mdrrmo', 'drrm_officer']) ? 'MDRRMO' : str_replace('_', ' ', Auth::user()->role->slug)) }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="#" title="Sign out" onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
          Logout
        </a>
      </li>
    </ul>
  </nav>
  <form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>

  {{-- ═══ SIDEBAR ═══ --}}
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    {{-- Brand (reference-style compact header) --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="/assets/logo/baras_seal_l.png" width="41" height="43"
           alt="Municipality of Baras seal" class="brand-image drms-municipal-seal elevation-2">
      <span class="brand-text font-weight-light drms-brand-erp">
        DRMS
        <small class="d-block drms-brand-erp-sub">Disaster Response &amp; Volunteer Matching System</small>
      </span>
    </a>

    <div class="sidebar">

      {{-- User Panel (reference-style compact profile card) --}}
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://drvms.freedev.app/assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
               alt="{{ Auth::user()->name }}" style="width:2.1rem;height:2.1rem;object-fit:cover;">
        </div>
        <div class="info drms-user-info">
          @php $profileUrl = \Illuminate\Support\Facades\Route::has('profile') ? route('profile') : '#'; @endphp
          @php
            $accessLabel = match (Auth::user()->role->slug) {
              'super_admin' => 'System administration',
              'mdrrmo', 'drrm_officer' => 'Disaster response coordination',
              'lgu_staff', 'warehouse_staff' => 'Inventory and LGU operations',
              'evac_manager', 'evacuation_manager' => 'Evacuation center operations',
              'donor' => 'Donor portal',
              'volunteer' => 'Volunteer portal',
              'resident' => 'Resident portal',
              'supplier' => 'Supplier portal',
              default => 'Account access',
            };
          @endphp
          <a href="{{ $profileUrl }}" class="d-block">{{ in_array(Auth::user()->role->slug, ['mdrrmo', 'drrm_officer']) ? 'MDRRMO' : (in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']) ? 'LGU Staff' : (Auth::user()->role->slug === 'super_admin' ? 'admin' : Auth::user()->name)) }}</a>
          <small class="text-muted">{{ $accessLabel }}</small>
        </div>
      </div>

      {{-- Sidebar Menu --}}
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
            data-widget="treeview" role="menu" data-accordion="false">

          @if(Auth::user()->role->slug !== 'donor')
            <li class="nav-header">OPERATIONS</li>
          @endif

          @if(Auth::user()->role->slug !== 'donor')
            <li class="nav-item">
              <a href="{{ route('dashboard') }}"
                 class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
          @endif

          @if(!in_array(Auth::user()->role->slug, ['donor', 'lgu_staff', 'warehouse_staff', 'evac_manager', 'evacuation_manager']))
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>User Management</p>
            </a>
          </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo', 'drrm_officer', 'evacuation_manager', 'evac_manager']))
            <li class="nav-item">
              <a href="{{ route('evacuation.index') }}"
                 class="nav-link {{ request()->routeIs('evacuation.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Evacuation Centers</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo', 'lgu_staff', 'drrm_officer', 'warehouse_staff', 'evac_manager', 'evacuation_manager']))
            <li class="nav-item">
              <a href="{{ route('inventory.index') }}"
                 class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>Emergency Supplies</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo', 'lgu_staff', 'drrm_officer', 'evac_manager', 'evacuation_manager']))
            <li class="nav-item">
              <a href="{{ route('relief.index') }}"
                 class="nav-link {{ request()->routeIs('relief.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-truck"></i>
                <p>Distributions</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo', 'lgu_staff', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('donations.index') }}"
                 class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-donate"></i>
                <p>Donations</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('donations.payment.history') }}"
                 class="nav-link {{ request()->routeIs('donations.payment.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                <p>Payment History</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'mdrrmo', 'lgu_staff', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('reports.index') }}"
                 class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>Reports & Analytics</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('audit.index') }}"
                 class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-history"></i>
                <p>Audit Trail</p>
              </a>
            </li>
          @endif

          @if(Auth::user()->role->slug === 'donor')
            <li class="nav-header">MY PORTAL</li>
            <li class="nav-item">
              <a href="{{ route('donor.index') }}" class="nav-link {{ request()->routeIs('donor.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-heart"></i>
                <p>My Donations</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('donate') }}" class="nav-link {{ request()->routeIs('donate*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-donate"></i>
                <p>Make a Donation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('donations.track') }}" class="nav-link {{ request()->routeIs('donations.track') ? 'active' : '' }}">
                <i class="nav-icon fas fa-search"></i>
                <p>Track a Donation</p>
              </a>
            </li>
          @endif

          <li class="nav-header">PUBLIC</li>

          @if(Auth::user()->role->slug !== 'donor')
            <li class="nav-item">
              <a href="#" class="nav-link disabled" onclick="event.preventDefault(); return false;" aria-disabled="true" tabindex="-1">
                <i class="nav-icon fas fa-bullhorn"></i>
                <p>Report incident</p>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a href="https://drvms.freedev.app/evac-centers" class="nav-link" target="_blank" rel="noopener">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>Evacuation centers</p>
            </a>
          </li>

          <li class="nav-header">SYSTEM</li>

          <li class="nav-item">
            <a href="{{ $profileUrl }}" class="nav-link {{ Route::has('profile') && request()->routeIs('profile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>My Profile</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

  {{-- ═══ CONTENT WRAPPER ═══ --}}
  <div class="content-wrapper">

    {{-- Content Header --}}
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Home</a>
              </li>
              @yield('breadcrumb')
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content --}}
    <div class="content">
      <div class="container-fluid">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        @endif

        @yield('content')

      </div>
    </div>
  </div>

  {{-- ═══ FOOTER ═══ --}}
  <footer class="main-footer">
    <strong>DRMS</strong> &middot; Disaster Response &amp; Volunteer Matching System
  </footer>

</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="/assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).on('click', '.nav-notification-link', function (event) {
    var link = this;
    var readUrl = $(link).data('read-url');
    var $badge = $('#navNotifCount');
    var count = parseInt($badge.text(), 10) || 0;

    event.preventDefault();
    $.ajax({
      url: readUrl,
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).always(function () {
      count = Math.max(0, count - 1);
      $badge.text(count).toggle(count > 0);
      window.location.href = link.href;
    });
  });

  // Suppress DataTables default alert and provide a safe initializer.
  try {
    $.fn.dataTable.ext.errMode = 'none';
  } catch (e) { /* ignore if DataTables not loaded yet */ }

  function safeInit($table, options) {
    if (!$table || $table.length === 0) return null;
    var theadCount = $table.find('thead tr th').length;
    var firstRow = $table.find('tbody tr:first');
    var tbodyCount = firstRow.length ? firstRow.find('td').length : 0;

    // If tbody is empty (no rows), allow init — DataTables handles empty tables.
    if ($table.find('tbody tr').length === 0) {
      return $table.DataTable(options);
    }

    // If first body row is a single cell with colspan matching the header, accept it.
    if (tbodyCount === 1) {
      var colspan = parseInt(firstRow.find('td').attr('colspan') || 0, 10);
      if (colspan === theadCount) {
        return $table.DataTable(options);
      }
    }

    if (theadCount !== tbodyCount) {
      console.warn('safeInit: skipping DataTable init due to column count mismatch', {
        id: $table.attr('id'), thead: theadCount, tbodyFirstRow: tbodyCount
      });
      return null;
    }

    return $table.DataTable(options);
  }
</script>

@stack('scripts')
</body>
</html>

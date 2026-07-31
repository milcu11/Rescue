<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RescuePH — @yield('title', 'Dashboard')</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- AdminLTE v3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

  <style>
    /* ── Sidebar dark red ── */
    .main-sidebar, .sidebar { background-color: #7B1113 !important; }

    .brand-link {
      background-color: #6A0F10 !important;
      border-bottom: 1px solid #9B1416 !important;
    }

    .sidebar-dark-danger .nav-sidebar > .nav-item > .nav-link.active,
    .sidebar-dark-danger .nav-sidebar > .nav-item > .nav-link:hover {
      background-color: #9B1416 !important;
    }

    .nav-sidebar .nav-link,
    .nav-sidebar .nav-header,
    .brand-link .brand-text {
      color: #fff !important;
    }

    .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.75) !important; }
    .nav-sidebar .nav-link.active .nav-icon { color: #fff !important; }
    .nav-sidebar .nav-header { color: rgba(255,255,255,0.5) !important; }

    /* ── Sidebar user panel ── */
    .user-panel { border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
    .user-panel .info a { color: #fff !important; }
    .user-panel .info small { color: rgba(255,255,255,0.6) !important; }

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

    /* ── Content header ── */
    .content-header h1 { font-size: 1.5rem; font-weight: 600; }
  </style>

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  {{-- ═══ NAVBAR ═══ --}}
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Public Site</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">

      {{-- Notifications Bell --}}
      <li class="nav-item dropdown">
        @php
          $currentUserId = auth()->id() ?? 1;
          $bellCount = \App\Models\Notification::where(function($q) use ($currentUserId) {
              $q->whereNull('user_id')->orWhere('user_id', $currentUserId);
          })->where('is_read', false)->count();

          $recentNotifs = \App\Models\Notification::where(function($q) use ($currentUserId) {
              $q->whereNull('user_id')->orWhere('user_id', $currentUserId);
          })->where('is_read', false)->latest()->take(5)->get();
        @endphp
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if($bellCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $bellCount }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
            {{ $bellCount }} Notification{{ $bellCount !== 1 ? 's' : '' }}
          </span>
          <div class="dropdown-divider"></div>
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
            <a href="{{ $n->link ?? route('notifications.index') }}" class="dropdown-item">
              <i class="{{ $icon }} mr-2"></i>
              {{ \Illuminate\Support\Str::limit($n->title, 35) }}
              <span class="float-right text-muted text-sm">
                {{ $n->created_at->diffForHumans() }}
              </span>
            </a>
            <div class="dropdown-divider"></div>
          @empty
            <span class="dropdown-item text-muted">No new notifications</span>
            <div class="dropdown-divider"></div>
          @endforelse
          <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">
            See All Notifications
          </a>
        </div>
      </li>

      {{-- Admin user --}}
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
          <i class="far fa-user mr-1"></i>
          {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <span class="dropdown-item-text text-muted" style="font-size:12px;">
            {{ ucfirst(str_replace('_', ' ', Auth::user()->role->slug)) }}
          </span>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
              <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
          </form>
        </div>
      </li>

    </ul>
  </nav>

  {{-- ═══ SIDEBAR ═══ --}}
  <aside class="main-sidebar sidebar-dark-danger elevation-4">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
           alt="RescuePH" class="brand-image img-circle elevation-3"
           style="opacity:.8; background:#fff; padding:2px;">
      <span class="brand-text font-weight-bold">RescuePH</span>
    </a>

    <div class="sidebar">

      {{-- User Panel --}}
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg"
               class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
          <small>{{ ucfirst(str_replace('_', ' ', Auth::user()->role->slug)) }}</small>
        </div>
      </div>

      {{-- Sidebar Menu --}}
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
            data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-header">OPERATIONS</li>

          <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Incidents</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-hands-helping"></i>
              <p>Volunteers</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>Volunteer Matching</p>
            </a>
          </li>

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer', 'warehouse_staff']))
            <li class="nav-item">
              <a href="{{ route('inventory.index') }}"
                 class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>Relief Goods</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('relief.index') }}"
                 class="nav-link {{ request()->routeIs('relief.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-truck"></i>
                <p>Distributions</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer', 'evacuation_manager']))
            <li class="nav-item">
              <a href="{{ route('evacuation.index') }}"
                 class="nav-link {{ request()->routeIs('evacuation.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Evacuation Centers</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer']))
            <li class="nav-item">
              <a href="{{ route('donations.index') }}"
                 class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-donate"></i>
                <p>Donations</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer', 'warehouse_staff']))
            <li class="nav-item">
              <a href="{{ route('donations.payment.history') }}"
                 class="nav-link {{ request()->routeIs('donations.payment.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                <p>Payment History</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer', 'warehouse_staff', 'evacuation_manager']))
            <li class="nav-item">
              <a href="{{ route('notifications.index') }}"
                 class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bell"></i>
                <p>Notifications
                  @if($bellCount > 0)
                    <span class="badge badge-danger right">{{ $bellCount }}</span>
                  @endif
                </p>
              </a>
            </li>
          @endif

          @if(Auth::user()->role->slug === 'donor')
            <li class="nav-item">
              <a href="{{ route('donor.index') }}"
                 class="nav-link {{ request()->routeIs('donor.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-donate"></i>
                <p>Donor Portal</p>
              </a>
            </li>
          @endif

          @if(in_array(Auth::user()->role->slug, ['super_admin', 'drrm_officer', 'warehouse_staff']))
            <li class="nav-item">
              <a href="{{ route('reports.index') }}"
                 class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>Reports</p>
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

          <li class="nav-header">SYSTEM</li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-globe"></i>
              <p>Public Homepage</p>
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
    <strong>RescuePH</strong> &copy; 2026. For thesis/capstone purposes only.
  </footer>

</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
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

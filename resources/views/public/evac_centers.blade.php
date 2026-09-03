<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Unified disaster response for LGUs — incidents, volunteers, relief, evacuation, and accountable operations.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Evacuation Centers</title>
    <link rel="icon" type="image/png" sizes="41x43" href="https://drvms.freedev.app/assets/logo/baras_seal_xs.png">
    <link rel="apple-touch-icon" sizes="185x193" href="https://drvms.freedev.app/assets/logo/baras_seal_l.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 4 (from AdminLTE bundle – same as dashboard for consistency) -->
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/css/public-home.css?v=1787802163">

    <script>
        window.DRMS_WEATHER_URL = "https:\/\/drvms.freedev.app\/api\/weather\/current";
    </script>

    <!-- Lucide Icons (homepage only – lightweight SVG icons) -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

    <style>
        :root {
            --drms-primary: #c62828;
            --drms-primary-dark: #6d1f2a;
            --drms-primary-deep: #3d1419;
            --drms-primary-soft: #fdeaea;
            --drms-accent: #e65100;
            --drms-accent-soft: #fff3e0;
            --drms-light: #f9f5f4;
            --drms-ink: #2c1819;
            --drms-muted: #6b5b5b;
            --drms-radius: 14px;
            --drms-radius-sm: 10px;
            --drms-shadow: 0 10px 40px rgba(109, 31, 42, 0.12);
            --drms-shadow-sm: 0 4px 18px rgba(109, 31, 42, 0.08);
        }

        body.drms-public-body {
            font-family: 'DM Sans', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--drms-ink);
            background: #fff;
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar */
        .drms-nav {
            background: linear-gradient(90deg, var(--drms-primary-deep) 0%, var(--drms-primary-dark) 55%, #8b2635 100%) !important;
            box-shadow: 0 4px 24px rgba(61, 20, 25, 0.25);
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }
        .drms-nav .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .drms-nav .nav-link {
            color: rgba(255, 255, 255, 0.88) !important;
            font-weight: 500;
            padding-left: 0.85rem !important;
            padding-right: 0.85rem !important;
        }
        .drms-nav .nav-link:hover {
            color: #fff !important;
        }
        @media (min-width: 992px) {
            .drms-nav .drms-nav-dropdown:hover > .dropdown-menu {
                display: block;
                margin-top: 0;
            }
        }
        .drms-nav .dropdown-menu {
            border: none;
            border-radius: var(--drms-radius-sm);
            box-shadow: var(--drms-shadow);
            padding: 0.35rem 0;
        }
        .drms-nav .dropdown-item {
            font-weight: 500;
            padding: 0.45rem 1.1rem;
        }
        .drms-nav .dropdown-item:hover {
            background: var(--drms-primary-soft);
            color: var(--drms-primary-dark);
        }
        .drms-nav img.brand-icon {
            width: 34px;
            height: 36px;
            object-fit: contain;
            flex: 0 0 auto;
        }
        .btn-drms-light {
            background: #fff;
            color: var(--drms-primary);
            font-weight: 600;
            border: none;
            border-radius: 999px;
            box-shadow: var(--drms-shadow-sm);
        }
        .btn-drms-light:hover {
            background: var(--drms-primary-soft);
            color: var(--drms-primary-dark);
        }
        .icon-inline {
            width: 18px;
            height: 18px;
            vertical-align: middle;
            display: inline-block;
        }

        /* Map and markers */
        .drms-evac-map {
            height: 380px;
            min-height: 320px;
            width: 100%;
            z-index: 1;
        }
        .drms-evac-marker-wrap {
            background: transparent;
            border: none;
        }
        .drms-evac-marker {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 3px solid;
            color: #fff;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        }
        .drms-evac-marker--nearest {
            box-shadow: 0 0 0 3px rgba(255, 235, 59, 0.6), 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .drms-evac-legend {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 4px;
        }
        .drms-evac-legend--open {
            background: #2e7d32;
            border: 2px solid #1b5e20;
        }
        .drms-evac-legend--full {
            background: #757575;
            border: 2px solid #424242;
        }
        .drms-evac-legend--nearest {
            background: #1b5e20;
            border: 2px solid #ffeb3b;
        }
        .drms-evac-row {
            cursor: pointer;
        }

        /* Modal headers (public pages) */
        .drms-public-theme .modal-content {
            border: none;
            border-radius: var(--drms-radius-sm);
            overflow: hidden;
            box-shadow: var(--drms-shadow);
        }
        .drms-public-theme .modal-header {
            position: relative;
            background: linear-gradient(135deg, var(--drms-primary-deep) 0%, var(--drms-primary-dark) 45%, var(--drms-primary) 100%);
            color: #fff;
            border-bottom: none;
            padding: 1rem 1.25rem;
            align-items: center;
        }
        .drms-public-theme .modal-header::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            pointer-events: none;
        }
        .drms-public-theme .modal-header .modal-title {
            font-weight: 600;
            color: #fff;
        }
        .drms-public-theme .modal-header .close {
            color: #fff;
            text-shadow: none;
            opacity: 0.88;
        }
        .drms-public-theme .modal-header .close:hover,
        .drms-public-theme .modal-header .close:focus {
            color: #fff;
            opacity: 1;
        }
        .drms-public-theme .modal-header.bg-success {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 50%, #43a047 100%) !important;
            color: #fff !important;
        }
        .drms-public-theme .modal-header.bg-warning {
            background: linear-gradient(135deg, #bf360c 0%, #e65100 50%, #fb8c00 100%) !important;
            color: #fff !important;
        }
        .drms-public-theme .modal-header.bg-danger {
            background: linear-gradient(135deg, var(--drms-primary-deep) 0%, var(--drms-primary) 55%, #e53935 100%) !important;
            color: #fff !important;
        }
        .drms-public-theme .modal-footer {
            border-top: 1px solid rgba(109, 31, 42, 0.12);
            background: var(--drms-light);
        }
        .drms-public-theme .modal-body {
            color: var(--drms-ink);
        }
        .drms-public-theme .modal-body .text-muted {
            color: var(--drms-muted) !important;
        }
        .drms-public-theme .modal-footer .btn-secondary {
            color: var(--drms-ink);
            background: #fff;
            border-color: rgba(109, 31, 42, 0.25);
        }

        /* Footer */
        .drms-footer {
            background: linear-gradient(180deg, var(--drms-primary-deep) 0%, #1a0f10 100%);
            color: #d4b8b8;
            padding: 3rem 0 1.75rem;
        }
        .drms-footer h5, .drms-footer h6 {
            letter-spacing: -0.02em;
        }
        .drms-footer-links a {
            color: #e8c4c4;
            text-decoration: none;
            display: inline-block;
            padding: 0.2rem 0;
            transition: color 0.15s ease;
        }
        .drms-footer-links a:hover {
            color: #fff;
        }
        img.footer-brand-icon {
            width: 38px;
            height: 40px;
            object-fit: contain;
            flex: 0 0 auto;
        }
    </style>
</head>
<body class="drms-public-body drms-public-theme">
<nav class="navbar navbar-expand-lg navbar-dark drms-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="https://drvms.freedev.app/">
            <img src="https://drvms.freedev.app/assets/logo/baras_seal_xs.png" width="41" height="43" class="brand-icon mr-2" alt="Municipality of Baras seal">
            <span><strong>DRMS</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item dropdown drms-nav-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navExplore" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Explore
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navExplore">
                        <a class="dropdown-item" href="https://drvms.freedev.app/#modules">System modules</a>
                        <a class="dropdown-item" href="https://drvms.freedev.app/#public-services">Public services</a>
                        <a class="dropdown-item" href="https://drvms.freedev.app/#lgu-updates">LGU Facebook updates</a>
                        <a class="dropdown-item" href="https://drvms.freedev.app/#location">Map &amp; weather</a>
                    </div>
                </li>
                <li class="nav-item dropdown drms-nav-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navCitizen" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Citizen tools
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navCitizen">
                        <a class="dropdown-item" href="https://drvms.freedev.app/report">Report incident</a>
                        <a class="dropdown-item" href="{{ route('public.evac_centers') }}">Evacuation centers</a>
                        <a class="dropdown-item" href="https://drvms.freedev.app/register">Register</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://drvms.freedev.app/#location">Map &amp; weather</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://drvms.freedev.app/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-drms-light btn-sm ml-lg-2 mt-2 mt-lg-0" href="{{ route('login') }}">
                        <i data-lucide="log-in" class="icon-inline"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <h1 class="h3 mb-2">Evacuation centers — Municipality of Baras</h1>
        <p class="text-muted mb-4">Find the nearest open shelter with available capacity. Tap a pin on the map for details.</p>

        <div id="evacNearestBanner" class="alert alert-info border-left border-info @if(!$nearestCenter) d-none @endif" style="border-left-width:4px!important">
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Nearest available center:</strong>
            <span id="evacNearestText">
                @if($nearestCenter)
                    {{ $nearestCenter['name'] }} — {{ $nearestCenter['distance_km'] ?? '0.8' }} km away,
                    {{ $nearestCenter['available_slots'] }} slots open
                    @if(!empty($nearestCenter['address']))
                        <br><small class="text-muted">{{ $nearestCenter['address'] }}</small>
                    @endif
                @endif
            </span>
            <small id="evacNearestNote" class="d-block text-muted mt-2 mb-0">
                Based on the municipal office default map center — not your phone GPS yet.
                Click <strong>Use my location</strong> on the map for automatic nearest-center detection.
            </small>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between">
                <strong>Open centers on map</strong>
                <button type="button" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0" id="btnEvacUseGps">
                    <i class="fas fa-crosshairs"></i> Use my location
                </button>
            </div>
            <div class="card-body p-0">
                <div id="drmsEvacMap" class="drms-evac-map"></div>
                <div class="px-3 py-2 border-top small text-muted">
                    <span class="drms-evac-legend drms-evac-legend--open"></span> Open with slots
                    <span class="drms-evac-legend drms-evac-legend--full ml-3"></span> Full
                    <span class="drms-evac-legend drms-evac-legend--nearest ml-3"></span> Nearest to you
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white"><strong>Open centers</strong></div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Name</th><th>Barangay</th><th>Occupancy</th><th>Families</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($centersData as $c)
                                    <tr data-evac-id="{{ $c['id'] }}" class="drms-evac-row">
                                        <td>{{ $c['name'] }}</td>
                                        <td>{{ $c['barangay_name'] }}</td>
                                        <td>{{ $c['occupancy'] }} / {{ $c['capacity'] }}</td>
                                        <td>{{ $c['families_count'] ?? 0 }}</td>
                                        <td>{{ ucfirst($c['status'] ?? 'Open') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No evacuation centers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4" id="evacIntakeCard">
                    <div class="card-header bg-white">
                        <strong><i class="fas fa-clipboard-list text-danger mr-1"></i> Before you go — procedure &amp; requirements</strong>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Select a center below to see its check-in steps and what your family must bring. Only proceed if the center is <strong>OPEN</strong> with available slots.</p>
                        <div id="evacIntakeContent" class="small">
                            <p class="text-muted mb-0">Choose an evacuation center in the registration form to view intake details.</p>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h5">Register family at center</h2>
                        <p class="small text-muted">Pre-register online before arrival. You will receive a <strong>FAM-</strong> QR token for check-in at the registration desk.</p>
                        <form action="{{ route('public.evac_centers.register_family') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Evacuation center</label>
                                <select name="evacuation_center_id" id="evacCenterSelect" class="form-control" required>
                                    @foreach($centersData as $c)
                                        <option value="{{ $c['id'] }}">{{ $c['name'] }} — {{ ucfirst($c['status'] ?? 'Open') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family head name</label>
                                <input type="text" name="family_head_name" class="form-control" required placeholder="Full name as shown on valid ID" value="{{ old('family_head_name') }}">
                                <small class="form-text text-muted">Must match the government ID presented at the registration desk.</small>
                            </div>
                            <div class="form-group">
                                <label>Number of family members</label>
                                <input type="number" name="members_count" class="form-control" value="{{ old('members_count', 1) }}" min="1" required>
                                <small class="form-text text-muted">Include infants and elderly who will stay at the shelter.</small>
                            </div>
                            <div class="form-group">
                                <label>Medical needs (if any)</label>
                                <input type="text" name="medical_needs" class="form-control" placeholder="e.g. insulin, wheelchair, pregnancy" value="{{ old('medical_needs') }}">
                                <small class="form-text text-muted">Helps staff prepare assistance — not a substitute for bringing medicines.</small>
                            </div>
                            <div class="form-group">
                                <label>Your mobile number</label>
                                <input type="text" name="contact_phone" class="form-control" placeholder="Family contact while at the center" value="{{ old('contact_phone') }}">
                                <small class="form-text text-muted">Your personal phone so staff can reach your family — <em>not</em> the shelter hotline.</small>
                            </div>

                            <!-- Honeypot anti-bot protection -->
                            <div style="display:none !important; visibility:hidden !important; position:absolute; left:-9999px;" aria-hidden="true">
                                <label for="drms_hp_website">Website field (leave blank)</label>
                                <input type="text" name="drms_website_hp" id="drms_hp_website" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <!-- Visual CAPTCHA protection -->
                            <div class="form-group mb-3">
                                <label for="drmsCaptchaInput" class="font-weight-bold d-block">Security Verification <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ route('public.captcha') }}?_t={{ time() }}" id="drmsCaptchaImg" alt="Security CAPTCHA" class="border rounded" style="height:44px; width:160px; object-fit:contain; background:#2b1b1d;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="document.getElementById('drmsCaptchaImg').src='{{ route('public.captcha') }}?_t='+Date.now()" title="Refresh security code">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                                <input type="text" name="captcha_code" id="drmsCaptchaInput" class="form-control" placeholder="Enter the 5 characters above" maxlength="6" required autocomplete="off" style="max-width:240px; text-transform:uppercase; letter-spacing:2px;">
                                <small class="text-muted">Type the letters and numbers shown above to verify you are human.</small>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block">Register family</button>
                        </form>
                        <hr>
                        <h2 class="h5">Check in with QR token</h2>
                        <p class="text-muted small">At the center, present your <strong>FAM-</strong> token from registration. Staff will scan or enter it to admit your family.</p>
                        <form id="formCheckInFamily" action="{{ route('public.evac_centers.check_in_family') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="familyQrToken">Family QR token</label>
                                <input type="text" name="family_qr_token" id="familyQrToken" class="form-control" placeholder="FAM-XXXXXXXX" required autocomplete="off">
                                <small class="form-text text-muted">Paste or type the token from registration (starts with FAM-).</small>
                            </div>
                            <button type="submit" class="btn btn-outline-danger btn-block" id="btnCheckInFamily">
                                <i class="fas fa-qrcode mr-1"></i> Check in family
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkInResultModal" tabindex="-1" role="dialog" aria-labelledby="checkInResultTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" id="checkInModalHeader">
                    <h5 class="modal-title" id="checkInResultTitle">Family check-in</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="checkInModalBody">
                    <p class="text-muted mb-0">Loading…</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="drms-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://drvms.freedev.app/assets/logo/baras_seal_xs.png" width="41" height="43" class="footer-brand-icon mr-2" alt="Municipality of Baras seal">
                    <h5 class="mb-0 text-white font-weight-bold">DRMS</h5>
                </div>
                <p class="small text-muted mb-2">Disaster Response &amp; Volunteer Matching System</p>
                <p class="small text-muted mb-0">Serving <strong class="text-white-50">Municipality of Baras</strong>, Rizal · Version 1.0.0-dev</p>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Explore</h6>
                <ul class="list-unstyled small drms-footer-links mb-0">
                    <li><a href="https://drvms.freedev.app/#features">Features</a></li>
                    <li><a href="https://drvms.freedev.app/#disasters">Disaster resilience</a></li>
                    <li><a href="https://drvms.freedev.app/#location">Map &amp; weather</a></li>
                    <li><a href="https://drvms.freedev.app/#modules">System modules</a></li>
                    <li><a href="https://drvms.freedev.app/#modules">System modules</a></li>
                    <li><a href="https://drvms.freedev.app/#public-services">Public services</a></li>
                    <li><a href="https://drvms.freedev.app/about">About DRMS</a></li>
                    <li><a href="https://drvms.freedev.app/demo">Client demo</a></li>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h6 class="text-white font-weight-bold mb-3">Operations</h6>
                <ul class="list-unstyled small drms-footer-links mb-0">
                    <li><a href="{{ route('login') }}">Admin dashboard</a></li>
                    <li><a href="https://drvms.freedev.app/api/v1/health" target="_blank" rel="noopener">API health</a></li>
                    <li><a href="https://www.pagasa.dost.gov.ph/" target="_blank" rel="noopener">PAGASA official advisories</a></li>
                </ul>
                <p class="small text-muted mt-3 mb-0">Weather on this site is informational (Open-Meteo). Always follow official government warnings.</p>
            </div>
        </div>
        <hr class="border-secondary mt-4 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-left small text-muted mb-2 mb-md-0">
                &copy; 2026 Local Government Unit For Thesis/Capstone Purpose not for Production. All rights reserved.
            </div>
            <div class="col-md-6 text-center text-md-right small text-muted">
                DRMS · Municipality of Baras
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
(function () {
    function escapeHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    var mapCfg = {!! json_encode($mapCfg) !!};
    var centers = {!! json_encode($centersJson) !!};
    var centerIntake = {!! json_encode($centerIntakeJson) !!};

    function updateNearestBanner(nearest, source) {
        var banner = document.getElementById('evacNearestBanner');
        var textEl = document.getElementById('evacNearestText');
        var noteEl = document.getElementById('evacNearestNote');
        if (!banner || !textEl || !nearest || !nearest.id) {
            if (banner) banner.classList.add('d-none');
            return;
        }
        var slots = nearest.available_slots != null ? nearest.available_slots : Math.max(0, (nearest.capacity || 0) - (nearest.current_occupancy || 0));
        var html = escapeHtml(nearest.name || '') + ' — ' + escapeHtml(String(nearest.distance_km || '0')) + ' km away, ' + slots + ' slots open';
        if (nearest.address) {
            html += '<br><small class="text-muted">' + escapeHtml(nearest.address) + '</small>';
        }
        textEl.innerHTML = html;
        if (noteEl) {
            noteEl.textContent = source === 'gps'
                ? 'Based on your GPS location.'
                : 'Based on the municipal office default map center. Use "Use my location" for GPS-based results.';
        }
        banner.classList.remove('d-none');
    }

    function renderIntake(centerId) {
        var box = document.getElementById('evacIntakeContent');
        if (!box) return;
        var c = centerIntake.find(function (x) { return x.id === parseInt(centerId, 10); });
        if (!c) {
            box.innerHTML = '<p class="text-muted mb-0">Center details not available.</p>';
            return;
        }
        var html = '<h6 class="font-weight-bold mb-2">' + escapeHtml(c.name) + '</h6>';
        html += '<p class="mb-2"><span class="badge badge-' + (c.status === 'open' ? 'success' : 'secondary') + '">' + escapeHtml((c.status || 'open').toUpperCase()) + '</span>';
        if (c.available_slots > 0) {
            html += ' <span class="text-success">' + c.available_slots + ' slots open</span>';
        }
        html += '</p>';
        if (c.contact_person || c.contact_phone) {
            html += '<p class="mb-2"><strong>Center contact (not your personal number):</strong><br>';
            if (c.contact_person) html += escapeHtml(c.contact_person) + '<br>';
            if (c.contact_phone) html += '<i class="fas fa-phone"></i> ' + escapeHtml(c.contact_phone);
            html += '</p>';
        }
        if (c.intake_procedures) {
            html += '<p class="mb-1"><strong>Check-in procedure</strong></p><pre class="bg-light p-2 rounded small mb-2" style="white-space:pre-wrap;font-family:inherit;">' + escapeHtml(c.intake_procedures) + '</pre>';
        } else {
            html += '<p class="mb-2 text-muted">Ask staff at the registration desk for check-in steps.</p>';
        }
        if (c.required_items) {
            html += '<p class="mb-0"><strong>Bring / present</strong></p><pre class="bg-light p-2 rounded small mb-0" style="white-space:pre-wrap;font-family:inherit;">' + escapeHtml(c.required_items) + '</pre>';
        }
        box.innerHTML = html;
    }

    var sel = document.getElementById('evacCenterSelect');
    if (sel) {
        sel.addEventListener('change', function () { renderIntake(sel.value); });
        if (sel.value) renderIntake(sel.value);
    }

    function showCheckInModal(payload) {
        var $modal = $('#checkInResultModal');
        var $header = $('#checkInModalHeader');
        var $title = $('#checkInResultTitle');
        var $body = $('#checkInModalBody');
        var status = payload.status || 'error';
        var d = payload.data || {};
        var isOk = status === 'ok';
        var isAlready = status === 'already';

        $header.removeClass('bg-success bg-warning bg-danger text-white');
        if (isOk) {
            $header.addClass('bg-success text-white');
            $title.text('Check-in successful');
        } else if (isAlready) {
            $header.addClass('bg-warning text-white');
            $title.text('Already checked in');
        } else {
            $header.addClass('bg-danger text-white');
            $title.text('Check-in failed');
        }

        if (status === 'error' && !d.family_head_name) {
            $body.html('<p class="mb-0">' + escapeHtml(payload.message || 'Could not check in this family.') + '</p>');
        } else {
            var rows = [
                ['Family head', d.family_head_name],
                ['QR token', d.family_qr_token],
                ['Members', d.members_count != null ? String(d.members_count) : ''],
                ['Evacuation center', d.center_name],
                ['Barangay', d.center_barangay],
                ['Center address', d.center_address],
                ['Center hotline', d.center_contact],
                ['Medical needs', d.medical_needs || '—'],
                ['Contact phone', d.contact_phone || '—'],
                ['Registered', d.registered_at],
                ['Checked in', d.checked_in_at || '—']
            ];
            var html = '<p class="mb-3">' + escapeHtml(payload.message || '') + '</p>';
            html += '<dl class="row small mb-0">';
            rows.forEach(function (row) {
                if (!row[1] || row[1] === '—') {
                    if (row[0] === 'Medical needs' || row[0] === 'Contact phone') {
                        html += '<dt class="col-sm-4">' + escapeHtml(row[0]) + '</dt>';
                        html += '<dd class="col-sm-8">' + escapeHtml(row[1]) + '</dd>';
                    }
                    return;
                }
                html += '<dt class="col-sm-4">' + escapeHtml(row[0]) + '</dt>';
                html += '<dd class="col-sm-8">' + escapeHtml(String(row[1])) + '</dd>';
            });
            html += '</dl>';
            $body.html(html);
        }

        $modal.modal('show');
    }

    $('#formCheckInFamily').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $('#btnCheckInFamily');
        $btn.prop('disabled', true);
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (res) {
            showCheckInModal(res);
            if (res.status === 'ok') {
                $form.find('input[name=family_qr_token]').val('');
            }
        }).fail(function (xhr) {
            var res = xhr.responseJSON || { status: 'error', message: 'Server error. Please try again.' };
            showCheckInModal(res);
        }).always(function () {
            $btn.prop('disabled', false);
        });
    });

    var mapEl = document.getElementById('drmsEvacMap');
    if (!mapEl || typeof L === 'undefined' || !centers.length) {
        return;
    }

    var map = L.map(mapEl, { minZoom: 11, maxZoom: 17 }).setView(
        [mapCfg.center.lat, mapCfg.center.lng],
        mapCfg.zoom || 13
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var bounds = [];
    var markersById = {};

    function evacIcon(marker, size) {
        size = size || 36;
        return L.divIcon({
            className: 'drms-evac-marker-wrap',
            html: '<div class="drms-evac-marker' + (marker.is_nearest ? ' drms-evac-marker--nearest' : '') + '" style="background:' + marker.marker.marker_bg + ';border-color:' + marker.marker.border_color + ';width:' + size + 'px;height:' + size + 'px;">' +
                '<i class="fas ' + marker.marker.icon + '"></i></div>',
            iconSize: [size, size],
            iconAnchor: [size / 2, size / 2],
            popupAnchor: [0, -size / 2]
        });
    }

    function popupHtml(c) {
        var lines = ['<strong>' + c.name + '</strong>'];
        if (c.barangay_name) {
            lines.push('<div class="text-muted small">' + c.barangay_name + '</div>');
        }
        if (c.address) {
            lines.push('<div>' + c.address + '</div>');
        }
        lines.push('<div>Occupancy: ' + c.occupancy + ' / ' + c.capacity + '</div>');
        if (c.is_full) {
            lines.push('<div class="text-danger"><strong>Full</strong></div>');
        } else {
            lines.push('<div class="text-success">' + c.available_slots + ' slots available</div>');
        }
        if (c.contact_phone) {
            lines.push('<div><i class="fas fa-phone"></i> Center hotline: ' + c.contact_phone + '</div>');
        }
        if (c.contact_person) {
            lines.push('<div class="small text-muted">Focal: ' + c.contact_person + '</div>');
        }
        if (c.is_nearest) {
            lines.push('<div class="badge badge-warning mt-1">Nearest to you</div>');
        }
        return lines.join('');
    }

    centers.forEach(function (c) {
        var latLng = [c.latitude, c.longitude];
        var m = L.marker(latLng, { icon: evacIcon(c) }).addTo(map);
        m.bindPopup(popupHtml(c));
        markersById[c.id] = m;
        bounds.push(latLng);

        m.on('click', function () {
            document.querySelectorAll('.drms-evac-row').forEach(function (row) {
                row.classList.toggle('table-active', parseInt(row.getAttribute('data-evac-id'), 10) === c.id);
            });
        });
    });

    if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
    } else if (bounds.length === 1) {
        map.setView(bounds[0], 14);
    }

    var userMarker = null;

    function showUserLocation(lat, lng) {
        if (userMarker) {
            map.removeLayer(userMarker);
        }
        userMarker = L.circleMarker([lat, lng], {
            radius: 8,
            color: '#1565c0',
            fillColor: '#42a5f5',
            fillOpacity: 0.9,
            weight: 2
        }).addTo(map).bindPopup('You are here');

        fetch('{{ route("public.evac_centers.nearest") }}?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.nearest || !data.nearest.id) {
                    return;
                }
                updateNearestBanner(data.nearest, 'gps');
                var nearestId = data.nearest.id;
                Object.keys(markersById).forEach(function (id) {
                    var isNearest = parseInt(id, 10) === nearestId;
                    var center = centers.find(function (x) { return x.id === parseInt(id, 10); });
                    if (center) {
                        center.is_nearest = isNearest;
                        markersById[id].setIcon(evacIcon(center, isNearest ? 42 : 36));
                    }
                });
                if (markersById[nearestId]) {
                    markersById[nearestId].openPopup();
                }
            })
            .catch(function () {});
    }

    var btnGps = document.getElementById('btnEvacUseGps');
    if (btnGps) {
        btnGps.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Location is not supported on this device.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function (pos) {
                showUserLocation(pos.coords.latitude, pos.coords.longitude);
                map.setView([pos.coords.latitude, pos.coords.longitude], Math.max(map.getZoom(), 14));
            }, function () {
                alert('Could not get your location. Please browse the map manually.');
            });
        });
    }

    document.querySelectorAll('.drms-evac-row').forEach(function (row) {
        row.addEventListener('click', function () {
            var id = parseInt(row.getAttribute('data-evac-id'), 10);
            if (markersById[id]) {
                map.setView(markersById[id].getLatLng(), Math.max(map.getZoom(), 14));
                markersById[id].openPopup();
            }
        });
    });

    setTimeout(function () { map.invalidateSize(); }, 200);
})();
</script>
</body>
</html>

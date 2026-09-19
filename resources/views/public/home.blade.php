<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Unified disaster response for LGUs — incidents, volunteers, relief, evacuation, and accountable operations.">
    <title>DRMS — Disaster Response &amp; Volunteer Matching System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo/baras_seal_l.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Lucide Icons (homepage only – lightweight SVG icons) -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

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
        .drms-nav .navbar-brand { color: #fff !important; font-weight: 700; letter-spacing: -0.02em; }
        .drms-nav .nav-link { color: rgba(255, 255, 255, 0.88) !important; font-weight: 500; padding-left: 0.85rem !important; padding-right: 0.85rem !important; }
        .drms-nav .nav-link:hover { color: #fff !important; }
        @media (min-width: 992px) {
            .drms-nav .drms-nav-dropdown:hover > .dropdown-menu { display: block; margin-top: 0; }
        }
        .drms-nav .dropdown-menu { border: none; border-radius: var(--drms-radius-sm); box-shadow: var(--drms-shadow); padding: 0.35rem 0; }
        .drms-nav .dropdown-item { font-weight: 500; padding: 0.45rem 1.1rem; }
        .drms-nav .dropdown-item:hover { background: var(--drms-primary-soft); color: var(--drms-primary-dark); }
        .drms-nav img.brand-icon { width: 34px; height: 36px; object-fit: contain; flex: 0 0 auto; }
        .btn-drms-light { background: #fff; color: var(--drms-primary); font-weight: 600; border: none; border-radius: 999px; box-shadow: var(--drms-shadow-sm); }
        .btn-drms-light:hover { background: var(--drms-primary-soft); color: var(--drms-primary-dark); }
        .btn-drms-primary { background: var(--drms-primary); color: #fff; font-weight: 600; border: none; border-radius: 999px; }
        .btn-drms-primary:hover { background: var(--drms-primary-dark); color: #fff; }
        .icon-inline { width: 18px; height: 18px; vertical-align: middle; display: inline-block; }

        /* Hero carousel */
        .drms-home-carousel .carousel-item { height: 62vh; min-height: 420px; }
        .drms-carousel-slide { height: 100%; display: flex; align-items: center; background-size: cover; background-position: center; position: relative; }
        .drms-carousel-slide::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(61,20,25,0.88) 0%, rgba(61,20,25,0.55) 55%, rgba(61,20,25,0.25) 100%); }
        .drms-carousel-slide--1 { background-color: var(--drms-primary-deep); }
        .drms-carousel-slide--2 { background: linear-gradient(135deg, var(--drms-primary-deep) 0%, var(--drms-primary-dark) 60%, #8b2635 100%); }
        .drms-carousel-slide--3 { background: linear-gradient(135deg, #3d1419 0%, #6d1f2a 50%, #c62828 100%); }
        .drms-carousel-copy { position: relative; z-index: 1; }
        .badge-drms { background: var(--drms-primary); color: #fff; font-weight: 600; padding: 0.4em 0.9em; border-radius: 999px; }
        .drms-carousel-indicators li { background-color: rgba(255,255,255,0.5); }
        .drms-carousel-indicators .active { background-color: #fff; }

        /* Stats */
        .drms-stat-strip { background: var(--drms-light); border-top: 1px solid rgba(109,31,42,0.08); border-bottom: 1px solid rgba(109,31,42,0.08); }
        .stat-strip-value { display: block; font-size: 1.9rem; font-weight: 700; color: var(--drms-primary-dark); }
        .stat-strip-label { font-size: 0.82rem; color: var(--drms-muted); }

        /* Sections */
        .drms-section-alt { background: var(--drms-light); }
        .section-title { font-weight: 700; letter-spacing: -0.02em; color: var(--drms-ink); }
        .module-card { background: #fff; box-shadow: var(--drms-shadow-sm); border-radius: var(--drms-radius); transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .module-card:hover { transform: translateY(-3px); box-shadow: var(--drms-shadow); }
        .module-icon { width: 30px; height: 30px; color: var(--drms-primary); }
        .role-badge { display: inline-block; background: #fff; border: 1px solid rgba(109,31,42,0.15); box-shadow: var(--drms-shadow-sm); border-radius: 999px; padding: 0.5rem 1.1rem; font-weight: 600; color: var(--drms-primary-dark); }

        /* CTA */
        .drms-cta { background: linear-gradient(135deg, var(--drms-primary-deep) 0%, var(--drms-primary-dark) 55%, var(--drms-primary) 100%); }
        .text-white-75 { color: rgba(255,255,255,0.75); }

        /* Footer */
        .drms-footer { background: linear-gradient(180deg, var(--drms-primary-deep) 0%, #1a0f10 100%); color: #d4b8b8; padding: 3rem 0 1.75rem; }
        .drms-footer h5, .drms-footer h6 { letter-spacing: -0.02em; }
        .drms-footer-links a { color: #e8c4c4; text-decoration: none; display: inline-block; padding: 0.2rem 0; transition: color 0.15s ease; }
        .drms-footer-links a:hover { color: #fff; }
        img.footer-brand-icon { width: 38px; height: 40px; object-fit: contain; flex: 0 0 auto; }
    </style>
</head>
<body class="drms-public-body drms-public-theme">
<nav class="navbar navbar-expand-lg navbar-dark drms-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('public.home') }}">
            <img src="{{ asset('assets/logo/baras_seal_l.png') }}" width="34" height="36" class="brand-icon mr-2" alt="Municipality of Baras seal">
            <span><strong>DRMS</strong></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item dropdown drms-nav-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navExplore" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Explore</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navExplore">
                        <a class="dropdown-item" href="{{ route('public.home') }}#modules">System modules</a>
                        <a class="dropdown-item" href="{{ route('public.home') }}#public-services">Public services</a>
                        <a class="dropdown-item" href="{{ route('public.home') }}#roles">Who uses DRMS</a>
                    </div>
                </li>
                <li class="nav-item dropdown drms-nav-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navCitizen" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Citizen tools</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navCitizen">
                        <a class="dropdown-item" href="{{ route('public.evac_centers') }}">Evacuation centers</a>
                        <a class="dropdown-item" href="{{ route('donate') }}">Make a donation</a>
                        <a class="dropdown-item" href="{{ route('donations.track') }}">Track my donation</a>
                    </div>
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
    <!-- Hero carousel -->
    <div id="homeCarousel" class="carousel slide drms-home-carousel shadow-sm" data-ride="carousel" data-interval="6500">
        <ol class="carousel-indicators drms-carousel-indicators">
            <li data-target="#homeCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#homeCarousel" data-slide-to="1"></li>
            <li data-target="#homeCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="drms-carousel-slide drms-carousel-slide--1">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 drms-carousel-copy">
                                <span class="badge badge-drms mb-3">DRMS</span>
                                <h2 class="display-4 font-weight-bold text-white mb-3">DRMS — Municipality of Baras</h2>
                                <p class="lead text-white-75 mb-4">Unified disaster response for LGUs — incidents, volunteers, relief, evacuation, and accountable operations.</p>
                                <a href="{{ route('public.evac_centers') }}" class="btn btn-drms-light btn-lg mr-2 mb-2">Find evacuation centers</a>
                                <a href="#public-services" class="btn btn-outline-light btn-lg mb-2">Public services</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="drms-carousel-slide drms-carousel-slide--2">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 drms-carousel-copy">
                                <span class="badge badge-drms mb-3">Citizens &amp; Communities</span>
                                <h2 class="display-4 font-weight-bold text-white mb-3">Public tools anyone can use</h2>
                                <p class="lead text-white-75 mb-4">Find shelters, donate to relief efforts, and track your donation — no account required.</p>
                                <a href="{{ route('donate') }}" class="btn btn-drms-light btn-lg mr-2 mb-2">Donate now</a>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg mb-2">Staff login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="drms-carousel-slide drms-carousel-slide--3">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 drms-carousel-copy">
                                <span class="badge badge-drms mb-3">MDRRMO &amp; LGU Staff</span>
                                <h2 class="display-4 font-weight-bold text-white mb-3">Accountable relief operations</h2>
                                <p class="lead text-white-75 mb-4">Track inventory, evacuation occupancy, and donations with a full audit trail.</p>
                                <a href="{{ route('login') }}" class="btn btn-drms-light btn-lg mb-2">Open operations dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#homeCarousel" role="button" data-slide="prev" aria-label="Previous slide">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#homeCarousel" role="button" data-slide="next" aria-label="Next slide">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
    </div>

    <!-- Live operations snapshot -->
    <section class="drms-stat-strip py-4">
        <div class="container">
            <div class="row text-center text-lg-left align-items-center">
                <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="stat-strip-item">
                        <span class="stat-strip-value">{{ $stats['evacuation_centers'] }}</span>
                        <span class="stat-strip-label">Evacuation centers</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="stat-strip-item">
                        <span class="stat-strip-value">{{ $stats['volunteers'] }}</span>
                        <span class="stat-strip-label">Active volunteers</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="stat-strip-item">
                        <span class="stat-strip-value">{{ $stats['donations'] }}</span>
                        <span class="stat-strip-label">Donations recorded</span>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-strip-item">
                        <span class="stat-strip-value">{{ $stats['modules'] }}</span>
                        <span class="stat-strip-label">System modules</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Public services -->
    <section id="public-services" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Public &amp; community services</h2>
                <p class="text-muted col-lg-9 mx-auto mb-0">Anyone in Municipality of Baras can use these pages without staff credentials. MDRRMO manages evacuation and relief data behind the scenes.</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 module-card border-0">
                        <div class="card-body d-flex flex-column">
                            <i data-lucide="home" class="module-icon"></i>
                            <h5 class="font-weight-bold mt-2">Evacuation centers</h5>
                            <p class="text-muted small flex-grow-1">Find the nearest open shelter, see capacity, and register your family with a unique check-in token.</p>
                            <a href="{{ route('public.evac_centers') }}" class="btn btn-sm btn-drms-primary mt-2 align-self-start">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 module-card border-0">
                        <div class="card-body d-flex flex-column">
                            <i data-lucide="heart-handshake" class="module-icon"></i>
                            <h5 class="font-weight-bold mt-2">Make a donation</h5>
                            <p class="text-muted small flex-grow-1">Support relief operations with cash or in-kind donations, processed securely online.</p>
                            <a href="{{ route('donate') }}" class="btn btn-sm btn-drms-primary mt-2 align-self-start">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 module-card border-0">
                        <div class="card-body d-flex flex-column">
                            <i data-lucide="search" class="module-icon"></i>
                            <h5 class="font-weight-bold mt-2">Track my donation</h5>
                            <p class="text-muted small flex-grow-1">Check the status of a donation you made — no account needed.</p>
                            <a href="{{ route('donations.track') }}" class="btn btn-sm btn-drms-primary mt-2 align-self-start">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 module-card border-0">
                        <div class="card-body d-flex flex-column">
                            <i data-lucide="layout-dashboard" class="module-icon"></i>
                            <h5 class="font-weight-bold mt-2">Operations dashboard</h5>
                            <p class="text-muted small flex-grow-1">MDRRMO and LGU staff use a live dashboard with role-based modules.</p>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-drms-primary mt-2 align-self-start">Open</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules -->
    <section id="modules" class="py-5 drms-section-alt">
        <div class="container">
            <h2 class="section-title text-center mb-2">System modules</h2>
            <p class="text-center text-muted mb-5 col-lg-9 mx-auto">Each module supports a specific part of disaster response — from evacuation to audit-ready operations summaries.</p>
            <div class="row">
                @foreach([
                    ['icon' => 'layout-dashboard', 'badge' => 'Operations', 'title' => 'Operations Dashboard', 'desc' => 'Real-time statistics and operational alerts.'],
                    ['icon' => 'home', 'badge' => 'Relief', 'title' => 'Evacuation Center Management', 'desc' => 'Capacity monitoring, family registration, occupancy maps.'],
                    ['icon' => 'package', 'badge' => 'Operations', 'title' => 'Emergency Supplies Inventory', 'desc' => 'Stock levels, movements, and low-stock alerts.'],
                    ['icon' => 'truck', 'badge' => 'Relief', 'title' => 'Relief Distribution', 'desc' => 'Track relief operations and distributions to evacuees.'],
                    ['icon' => 'hand-coins', 'badge' => 'Relief', 'title' => 'Donations', 'desc' => 'Online donations, payment tracking, and donor portal.'],
                    ['icon' => 'users-cog', 'badge' => 'Platform', 'title' => 'User Management', 'desc' => 'Account approval, roles, and access control.'],
                    ['icon' => 'bell', 'badge' => 'Platform', 'title' => 'Notification System', 'desc' => 'Email and system alerts for deployments and updates.'],
                    ['icon' => 'file-bar-chart', 'badge' => 'Operations', 'title' => 'Reports &amp; Analytics', 'desc' => 'Charts, live KPIs, PDF/Excel export.'],
                    ['icon' => 'shield-check', 'badge' => 'Platform', 'title' => 'Audit Trail', 'desc' => 'Append-only activity log across the platform.'],
                ] as $module)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 module-card border-0">
                            <div class="card-body">
                                <span class="badge badge-danger badge-pill float-right small">{{ $module['badge'] }}</span>
                                <i data-lucide="{{ $module['icon'] }}" class="module-icon"></i>
                                <h5 class="font-weight-bold">{{ $module['title'] }}</h5>
                                <p class="text-muted small mb-0">{!! $module['desc'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Roles -->
    <section id="roles" class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-2">Who uses DRMS?</h2>
            <p class="text-center text-muted mb-5 col-lg-8 mx-auto">Each role sees only the modules they need — from full MDRRMO control to volunteer, evacuation manager, and donor access.</p>
            <div class="row justify-content-center">
                @foreach(['Super Admin', 'MDRRMO Staff', 'Volunteer', 'Evacuation Manager', 'Donor'] as $role)
                    <div class="col-6 col-md-4 col-lg-3 mb-3 text-center">
                        <span class="role-badge">{{ $role }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-center text-muted small mt-4 mb-0">Staff and volunteer accounts are created by administrators in User Management.</p>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 drms-cta">
        <div class="container text-center">
            <h2 class="text-white mb-3">Need to find shelter, donate, or run operations?</h2>
            <p class="text-white-75 mb-4">Use the public tools below or sign in if you are MDRRMO / LGU staff.</p>
            <div class="mb-4">
                <a href="{{ route('public.evac_centers') }}" class="btn btn-outline-light btn-lg mr-2 mb-2">Evacuation centers</a>
                <a href="{{ route('donate') }}" class="btn btn-outline-light btn-lg mr-2 mb-2">Donate</a>
                <a href="{{ route('login') }}" class="btn btn-drms-light btn-lg mb-2">Staff login</a>
            </div>
        </div>
    </section>
</main>

<footer class="drms-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('assets/logo/baras_seal_l.png') }}" width="38" height="40" class="footer-brand-icon mr-2" alt="Municipality of Baras seal">
                    <h5 class="mb-0 text-white font-weight-bold">DRMS</h5>
                </div>
                <p class="small text-muted mb-2">Disaster Response &amp; Volunteer Matching System</p>
                <p class="small text-muted mb-0">Serving <strong class="text-white-50">Municipality of Baras</strong>, Rizal</p>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Explore</h6>
                <ul class="list-unstyled small drms-footer-links mb-0">
                    <li><a href="#public-services">Public services</a></li>
                    <li><a href="#modules">System modules</a></li>
                    <li><a href="#roles">Who uses DRMS</a></li>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h6 class="text-white font-weight-bold mb-3">Operations</h6>
                <ul class="list-unstyled small drms-footer-links mb-0">
                    <li><a href="{{ route('public.evac_centers') }}">Evacuation centers</a></li>
                    <li><a href="{{ route('donate') }}">Make a donation</a></li>
                    <li><a href="{{ route('donations.track') }}">Track my donation</a></li>
                    <li><a href="{{ route('login') }}">Staff login</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-left small text-muted mb-2 mb-md-0">
                &copy; {{ now()->year }} Local Government Unit — For Thesis/Capstone Purpose, not for Production. All rights reserved.
            </div>
            <div class="col-md-6 text-center text-md-right small text-muted">
                DRMS · Municipality of Baras
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        } else {
            window.addEventListener('load', function () {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }
    });
</script>
</body>
</html>

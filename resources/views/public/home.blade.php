<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Unified disaster response for LGUs — incidents, response teams, relief, evacuation, and accountable operations.">
    <title>DRMS — Disaster Response Matching System</title>
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

        /* Map & weather */
        .drms-map-card { background: #fff; border-radius: var(--drms-radius); overflow: hidden; box-shadow: var(--drms-shadow); border: 1px solid #e2e8f0; }
        .drms-map-header { padding: 0.85rem 1.1rem; background: var(--drms-primary-soft); color: var(--drms-primary-dark); font-weight: 600; font-size: 0.95rem; gap: 0.75rem; flex-wrap: wrap; }
        .drms-map-frame-wrap { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #e2e8f0; }
        .drms-map-frame-wrap iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        .drms-windy-card .drms-map-header { border-bottom: 1px solid #e2e8f0; }
        .drms-windy-frame-wrap { position: relative; width: 100%; min-height: 420px; height: 52vw; max-height: 520px; background: #1a2332; }
        @media (min-width: 992px) { .drms-windy-frame-wrap { height: 480px; } }
        .drms-windy-frame-wrap iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        #windyOverlayBtns .btn-outline-primary { color: var(--drms-primary); border-color: var(--drms-primary); }
        #windyOverlayBtns .btn-outline-primary.active, #windyOverlayBtns .btn-outline-primary:hover { background: var(--drms-primary); color: #fff; }
        .icon-xs, .icon-xs svg { width: 14px; height: 14px; vertical-align: -2px; }
        .drms-weather-card { border-radius: var(--drms-radius); box-shadow: var(--drms-shadow); background: linear-gradient(160deg, #fff 0%, var(--drms-primary-soft) 100%); border: 1px solid #e2e8f0 !important; }
        .drms-weather-temp { font-size: 2.75rem; font-weight: 700; line-height: 1; color: var(--drms-primary-dark); letter-spacing: -0.04em; }
        .drms-weather-meta li { padding: 0.25rem 0; border-bottom: 1px solid rgba(198, 40, 40, 0.1); }
        .drms-weather-meta li:last-child { border-bottom: none; }
        .drms-weather-loading { padding: 1rem 0; }

        /* LGU Facebook updates */
        .drms-fb-screen { background: var(--drms-light, #f9f5f4); }
        .drms-fb-panel-wrapper { width: 100%; max-width: min(94vw, 640px); margin: 0 auto; }
        .drms-fb-panel { display: flex; flex-direction: column; width: 100%; background: #fff; border: 1px solid rgba(109, 31, 42, 0.16); border-radius: var(--drms-radius); box-shadow: var(--drms-shadow); overflow: hidden; }
        .drms-fb-panel-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding: 1rem 1.15rem; border-bottom: 1px solid rgba(45, 12, 18, 0.08); border-top: 4px solid var(--drms-primary); background: linear-gradient(180deg, #fff 0%, #fdf8f8 100%); }
        .drms-fb-panel-brand { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .drms-fb-panel-icon { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #1877f2; color: #fff; font-size: 1.15rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(24, 119, 242, 0.35); }
        .drms-fb-panel-brand-text { min-width: 0; }
        .drms-fb-panel-title { display: block; font-weight: 700; font-size: 1rem; color: var(--drms-primary-dark); line-height: 1.3; }
        .drms-fb-panel-sub { display: block; font-size: 0.82rem; color: var(--drms-muted); margin-top: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .drms-fb-panel-link { flex-shrink: 0; border-color: rgba(24, 119, 242, 0.45); color: #1877f2; }
        .drms-fb-panel-link:hover { background: #1877f2; border-color: #1877f2; color: #fff; }
        .drms-fb-panel-viewport { min-height: min(72vh, 680px); height: min(72vh, 680px); overflow: hidden; background: #f0f2f5; border-top: 1px solid rgba(45, 12, 18, 0.06); }
        .drms-fb-panel-viewport-inner { width: 100%; height: 100%; display: flex; justify-content: center; align-items: flex-start; overflow: hidden; padding: 0; }
        .drms-fb-screen-scaler { transform-origin: top center; will-change: transform; flex-shrink: 0; }
        .drms-fb-screen-scaler iframe { display: block; border: 0; background: #fff; }
        @media (min-width: 768px) { .drms-fb-panel-wrapper { max-width: min(88vw, 720px); } .drms-fb-panel-viewport { min-height: min(75vh, 720px); height: min(75vh, 720px); } }
        @media (min-width: 992px) { .drms-fb-panel-wrapper { max-width: min(78vw, 820px); } .drms-fb-panel-viewport { min-height: min(78vh, 760px); height: min(78vh, 760px); } }
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
                        <a class="dropdown-item" href="{{ route('public.home') }}#lgu-updates">LGU Facebook updates</a>
                        <a class="dropdown-item" href="{{ route('public.home') }}#location">Map &amp; weather</a>
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
                    <a class="nav-link" href="{{ route('public.home') }}#location">Map &amp; weather</a>
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
                                <p class="lead text-white-75 mb-4">Unified disaster response for LGUs — incidents, response teams, relief, evacuation, and accountable operations.</p>
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
                        <span class="stat-strip-label">Active responders</span>
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

    <!-- LGU Facebook updates -->
    <section id="lgu-updates" class="py-5 drms-section-alt drms-fb-screen">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5">
                <span class="badge badge-drms mb-2">Facebook Official Page</span>
                <h2 class="section-title mb-2">LGU Baras Rizal</h2>
                <p class="text-muted col-lg-8 mx-auto mb-0">
                    Official municipal announcements and advisories from LGU Baras Rizal.
                    Scroll inside the panel below to read the latest posts.
                </p>
            </div>

            <div class="drms-fb-panel-wrapper">
                <div class="drms-fb-panel">
                    <div class="drms-fb-panel-header">
                        <div class="drms-fb-panel-brand">
                            <span class="drms-fb-panel-icon" aria-hidden="true"><i class="fab fa-facebook-f"></i></span>
                            <div class="drms-fb-panel-brand-text">
                                <span class="drms-fb-panel-title">Facebook Official Page</span>
                                <span class="drms-fb-panel-sub">LGU Baras Rizal</span>
                            </div>
                        </div>
                        <a href="https://www.facebook.com/LGUBarasRizal" class="btn btn-sm btn-outline-primary drms-fb-panel-link" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt mr-1"></i> Open on Facebook
                        </a>
                    </div>
                    <div id="drms-fb-embed-wrap" class="drms-fb-panel-viewport" data-page-url="https://www.facebook.com/LGUBarasRizal">
                        <div class="drms-fb-panel-viewport-inner">
                            <div id="drms-fb-embed-scaler" class="drms-fb-screen-scaler">
                                <iframe
                                    id="drms-fb-embed-frame"
                                    title="LGU Baras Rizal — Facebook Official Page"
                                    src="about:blank"
                                    scrolling="yes"
                                    frameborder="0"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map & weather -->
    <section id="location" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Service area &amp; weather context</h2>
                <p class="text-muted col-lg-9 mx-auto mb-0">
                    Situational maps and rainfall readings for <strong>Municipality of Baras</strong>, Rizal.
                    Use alongside official advisories from <strong>PAGASA</strong> when planning deployments or public warnings.
                </p>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="drms-map-card drms-windy-card">
                        <div class="drms-map-header d-flex flex-wrap justify-content-between align-items-center">
                            <span><i data-lucide="wind" class="icon-inline"></i> Weather map — Municipality of Baras</span>
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="btn-group btn-group-sm mr-2 mb-1" id="windyOverlayBtns" role="group" aria-label="Weather layer">
                                    <button type="button" class="btn btn-outline-primary active" data-overlay="wind">Wind</button>
                                    <button type="button" class="btn btn-outline-primary" data-overlay="rain">Rain</button>
                                    <button type="button" class="btn btn-outline-primary" data-overlay="temp">Temp</button>
                                    <button type="button" class="btn btn-outline-primary" data-overlay="clouds">Clouds</button>
                                </div>
                                <a href="https://www.windy.com/?14.5171,121.2672,11,d:picker" class="btn btn-sm btn-outline-secondary mb-1" target="_blank" rel="noopener noreferrer">
                                    Full forecast <i data-lucide="external-link" class="icon-inline icon-xs"></i>
                                </a>
                            </div>
                        </div>
                        <p class="small text-muted px-3 pt-2 mb-0">Rain and wind layers help duty officers anticipate flood risk in low-lying barangays.</p>
                        <div class="drms-windy-frame-wrap" id="drms-windy" data-lat="14.5171" data-lon="121.2672" data-zoom="11">
                            <iframe id="windyEmbedFrame" title="Weather map — Municipality of Baras"
                                src="https://embed.windy.com/embed2.html?lat=14.5171&lon=121.2672&detailLat=14.5171&detailLon=121.2672&zoom=11&level=surface&overlay=wind&product=ecmwf&message=true&marker=&calendar=now&pressure=&type=map&location=coordinates&detail=true&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="drms-map-card">
                        <div class="drms-map-header d-flex justify-content-between align-items-center">
                            <span><i data-lucide="map-pin" class="icon-inline"></i> Municipality of Baras — service area</span>
                        </div>
                        <div class="drms-map-frame-wrap">
                            <iframe title="Map of Municipality of Baras"
                                src="https://maps.google.com/maps?q=14.5171%2C121.2672&hl=en&z=12&output=embed&iwloc=near"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card drms-weather-card border-0 h-100" id="drms-weather" data-lat="14.5171" data-lon="121.2672" data-weather-url="{{ route('public.weather.current') }}">
                        <div class="card-body">
                            <h5 class="font-weight-bold mb-1"><i data-lucide="cloud-sun" class="icon-inline"></i> Current conditions</h5>
                            <p class="small text-muted mb-3">Municipality of Baras · updated from live readings</p>
                            <div id="drms-weather-loading" class="drms-weather-loading text-muted small">Loading latest conditions…</div>
                            <div id="drms-weather-body" class="d-none">
                                <div class="drms-weather-temp" id="drms-weather-temp">—</div>
                                <p class="mb-2 text-muted small" id="drms-weather-desc"></p>
                                <ul class="list-unstyled small text-muted mb-0 drms-weather-meta">
                                    <li><span class="text-dark font-weight-bold">Feels like</span> <span id="drms-weather-apparent">—</span></li>
                                    <li><span class="text-dark font-weight-bold">Humidity</span> <span id="drms-weather-humidity">—</span></li>
                                    <li><span class="text-dark font-weight-bold">Wind</span> <span id="drms-weather-wind">—</span></li>
                                </ul>
                                <p class="small text-muted mt-3 mb-0" id="drms-weather-updated"></p>
                            </div>
                            <div id="drms-weather-error" class="alert alert-warning small mb-0 d-none" role="alert"></div>
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
            <p class="text-center text-muted mb-5 col-lg-8 mx-auto">Each role sees only the modules they need — from full MDRRMO control to response team, evacuation manager, and donor access.</p>
            <div class="row justify-content-center">
                @foreach(['Super Admin', 'MDRRMO Staff', 'Response Team', 'Evacuation Manager', 'Donor'] as $role)
                    <div class="col-6 col-md-4 col-lg-3 mb-3 text-center">
                        <span class="role-badge">{{ $role }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-center text-muted small mt-4 mb-0">Staff and response team accounts are created by administrators in User Management.</p>
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
                <p class="small text-muted mb-2">Disaster Response Matching System</p>
                <p class="small text-muted mb-0">Serving <strong class="text-white-50">Municipality of Baras</strong>, Rizal</p>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Explore</h6>
                <ul class="list-unstyled small drms-footer-links mb-0">
                    <li><a href="#public-services">Public services</a></li>
                    <li><a href="#lgu-updates">LGU Facebook updates</a></li>
                    <li><a href="#location">Map &amp; weather</a></li>
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

    // LGU Facebook updates — Facebook Page Plugin embed, scaled to fit the panel
    (function () {
        var wrap = document.getElementById('drms-fb-embed-wrap');
        var frame = document.getElementById('drms-fb-embed-frame');
        var scaler = document.getElementById('drms-fb-embed-scaler');
        if (!wrap || !frame || !scaler) return;

        var pageUrl = wrap.getAttribute('data-page-url');
        var FB_WIDTH = 500;
        var FB_HEIGHT = 700;
        var loaded = false;

        function buildSrc() {
            var params = new URLSearchParams({
                href: pageUrl,
                tabs: 'timeline',
                width: FB_WIDTH,
                height: FB_HEIGHT,
                small_header: 'false',
                adapt_container_width: 'false',
                hide_cover: 'false',
                show_facepile: 'true'
            });
            return 'https://www.facebook.com/plugins/page.php?' + params.toString();
        }

        function resize() {
            var scale = Math.min(1, wrap.clientWidth / FB_WIDTH);
            scaler.style.width = FB_WIDTH + 'px';
            scaler.style.height = FB_HEIGHT + 'px';
            scaler.style.transform = 'scale(' + scale + ')';
            frame.style.width = FB_WIDTH + 'px';
            frame.style.height = FB_HEIGHT + 'px';
        }

        function load() {
            if (loaded || !pageUrl) return;
            loaded = true;
            frame.src = buildSrc();
            resize();
        }

        // Only load once the panel scrolls into view, to avoid an upfront Facebook request on every page load.
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        load();
                        observer.disconnect();
                    }
                });
            }, { rootMargin: '200px' });
            observer.observe(wrap);
        } else {
            load();
        }

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(resize, 150);
        });
    })();

    // Windy overlay switcher
    (function () {
        var wrap = document.getElementById('drms-windy');
        var frame = document.getElementById('windyEmbedFrame');
        var btns = document.getElementById('windyOverlayBtns');
        if (!wrap || !frame || !btns) return;

        btns.addEventListener('click', function (e) {
            var btn = e.target.closest('button[data-overlay]');
            if (!btn) return;
            Array.prototype.forEach.call(btns.querySelectorAll('button'), function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            var lat = wrap.getAttribute('data-lat');
            var lon = wrap.getAttribute('data-lon');
            var zoom = wrap.getAttribute('data-zoom') || 11;
            frame.src = 'https://embed.windy.com/embed2.html?lat=' + lat + '&lon=' + lon +
                '&detailLat=' + lat + '&detailLon=' + lon + '&zoom=' + zoom +
                '&level=surface&overlay=' + btn.getAttribute('data-overlay') +
                '&product=ecmwf&message=true&marker=&calendar=now&pressure=&type=map' +
                '&location=coordinates&detail=true&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1';
        });
    })();

    // Live weather card
    (function () {
        function wmoLabel(code) {
            if (code === 0) return 'Clear sky';
            if (code === 1) return 'Mainly clear';
            if (code === 2) return 'Partly cloudy';
            if (code === 3) return 'Overcast';
            if (code >= 45 && code <= 48) return 'Fog';
            if (code >= 51 && code <= 67) return 'Rain';
            if (code >= 80 && code <= 82) return 'Rain showers';
            if (code >= 95) return 'Thunderstorm';
            return 'Current conditions';
        }

        var el = document.getElementById('drms-weather');
        if (!el) return;

        var lat = parseFloat(el.getAttribute('data-lat'));
        var lon = parseFloat(el.getAttribute('data-lon'));
        var loading = document.getElementById('drms-weather-loading');
        var body = document.getElementById('drms-weather-body');
        var errBox = document.getElementById('drms-weather-error');
        var apiUrl = el.getAttribute('data-weather-url');

        if (!apiUrl || Number.isNaN(lat) || Number.isNaN(lon)) {
            if (loading) loading.classList.add('d-none');
            if (errBox) {
                errBox.textContent = 'Weather is not configured.';
                errBox.classList.remove('d-none');
            }
            return;
        }

        var url = apiUrl + (apiUrl.indexOf('?') >= 0 ? '&' : '?') +
            'lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lon);

        fetch(url, { method: 'GET', credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (res) {
                return res.json().then(function (data) {
                    if (!res.ok || data.status !== 'ok') {
                        throw new Error(data.message || 'Weather unavailable');
                    }
                    return data;
                });
            })
            .then(function (data) {
                var cur = data.current;
                if (!cur) throw new Error('No weather data');

                if (loading) loading.classList.add('d-none');
                if (errBox) errBox.classList.add('d-none');
                if (body) body.classList.remove('d-none');

                var tempEl = document.getElementById('drms-weather-temp');
                var descEl = document.getElementById('drms-weather-desc');
                var appEl = document.getElementById('drms-weather-apparent');
                var humEl = document.getElementById('drms-weather-humidity');
                var windEl = document.getElementById('drms-weather-wind');
                var updatedEl = document.getElementById('drms-weather-updated');

                if (tempEl && cur.temperature_2m != null) {
                    tempEl.textContent = Math.round(cur.temperature_2m) + '°C';
                }
                if (descEl) {
                    descEl.textContent = data.description || wmoLabel(cur.weather_code);
                }
                if (appEl && cur.apparent_temperature != null) {
                    appEl.textContent = Math.round(cur.apparent_temperature) + '°C';
                }
                if (humEl && cur.relative_humidity_2m != null) {
                    humEl.textContent = Math.round(cur.relative_humidity_2m) + '%';
                }
                if (windEl && cur.wind_speed_10m != null) {
                    windEl.textContent = Math.round(cur.wind_speed_10m) + ' km/h';
                }
                if (updatedEl && cur.time) {
                    updatedEl.textContent = 'Updated: ' + String(cur.time).replace('T', ' ') + ' · ' + (data.source || 'Open-Meteo') +
                        (data.stale ? ' (cached)' : '');
                }

                if (typeof lucide !== 'undefined') lucide.createIcons();
            })
            .catch(function (err) {
                if (loading) loading.classList.add('d-none');
                if (body) body.classList.add('d-none');
                if (errBox) {
                    errBox.textContent = (err && err.message) ? err.message : 'Could not load weather. Please refresh the page.';
                    errBox.classList.remove('d-none');
                }
            });
    })();
</script>
</body>
</html>

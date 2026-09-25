<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Donation | DRMS</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <style>
    :root { --drms-primary: #c62828; --drms-primary-dark: #6d1f2a; --drms-primary-deep: #3d1419; --drms-primary-soft: #fdeaea; --drms-light: #f9f5f4; --drms-ink: #2c1819; --drms-radius-sm: 10px; --drms-shadow: 0 10px 40px rgba(109,31,42,.12); --drms-shadow-sm: 0 4px 18px rgba(109,31,42,.08); }
    body { font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif; background: var(--drms-light); color: var(--drms-ink); }
    .drms-nav { background: linear-gradient(90deg, var(--drms-primary-deep) 0%, var(--drms-primary-dark) 55%, #8b2635 100%) !important; box-shadow: 0 4px 24px rgba(61,20,25,.25); padding-top: .65rem; padding-bottom: .65rem; }
    .drms-nav .navbar-brand { color: #fff !important; font-weight: 700; letter-spacing: -.02em; }
    .drms-nav .nav-link { color: rgba(255,255,255,.88) !important; font-weight: 500; padding-left: .85rem !important; padding-right: .85rem !important; }
    .drms-nav .nav-link:hover { color: #fff !important; }
    @media (min-width: 992px) { .drms-nav .drms-nav-dropdown:hover > .dropdown-menu { display: block; margin-top: 0; } }
    .drms-nav .dropdown-menu { border: none; border-radius: var(--drms-radius-sm); box-shadow: var(--drms-shadow); padding: .35rem 0; }
    .drms-nav .dropdown-item { font-weight: 500; padding: .45rem 1.1rem; }
    .drms-nav .dropdown-item:hover { background: var(--drms-primary-soft); color: var(--drms-primary-dark); }
    .drms-nav img.brand-icon { width: 34px; height: 36px; object-fit: contain; flex: 0 0 auto; }
    .btn-drms-light { background: #fff; color: var(--drms-primary); font-weight: 600; border: none; border-radius: 999px; box-shadow: var(--drms-shadow-sm); }
    .btn-drms-light:hover { background: var(--drms-primary-soft); color: var(--drms-primary-dark); }
    .icon-inline { width: 18px; height: 18px; vertical-align: middle; display: inline-block; }
    .track-card { max-width: 680px; border: 0; border-radius: 14px; box-shadow: 0 10px 35px rgba(109,31,42,.1); }
    .track-card .card-header { background: #fff; border-bottom: 1px solid #eee; }
    .track-card .card-title { color: #6d1f2a; font-weight: 700; }
  </style>
</head>
<body class="drms-public-body">
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
          <li class="nav-item"><a class="nav-link" href="{{ route('public.home') }}#location">Map &amp; weather</a></li>
          <li class="nav-item"><a class="btn btn-drms-light btn-sm ml-lg-2 mt-2 mt-lg-0" href="{{ route('login') }}"><i data-lucide="log-in" class="icon-inline"></i> Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-5">
    <div class="text-center mb-4">
      <h1 class="font-weight-bold">Track your donation</h1>
      <p class="text-muted">Enter the tracking code from your donation receipt. No account is required.</p>
    </div>

    <div class="card track-card mx-auto">
      <div class="card-header">
        <h2 class="card-title h5 mb-0"><i class="fas fa-search mr-2"></i>Track by donation code</h2>
      </div>
      <div class="card-body p-4">
        <form method="GET" action="{{ route('donations.track') }}">
          <div class="input-group">
            <input type="text" name="code" class="form-control" value="{{ $code ?? '' }}" placeholder="e.g. DON-2024-0001" aria-label="Donation tracking code">
            <div class="input-group-append">
              <button type="submit" class="btn btn-danger"><i class="fas fa-search mr-1"></i>Track</button>
            </div>
          </div>
        </form>

        @if($error)
          <div class="alert alert-danger mt-3 mb-0"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</div>
        @endif

        @if($donation)
          <div class="mt-4">
            <table class="table table-borderless mb-0">
              <tr><th class="text-muted" style="width: 150px;">Tracking code</th><td><span class="badge badge-dark">{{ $donation->tracking_code }}</span></td></tr>
              <tr><th class="text-muted">Donor</th><td>{{ $donation->donor_name }}</td></tr>
              <tr><th class="text-muted">Type</th><td>{{ ucfirst($donation->type) }}</td></tr>
              <tr><th class="text-muted">Status</th><td>
                @if($donation->status === 'pending')
                  <span class="badge badge-warning">Pending — awaiting receipt</span>
                @elseif($donation->status === 'received')
                  <span class="badge badge-success">Received — thank you!</span>
                @else
                  <span class="badge badge-primary">Distributed to beneficiaries</span>
                @endif
              </td></tr>
              <tr><th class="text-muted">Date recorded</th><td>{{ $donation->created_at->format('M d, Y') }}</td></tr>
            </table>
          </div>
        @endif
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>document.addEventListener('DOMContentLoaded', function () { if (typeof lucide !== 'undefined') lucide.createIcons(); });</script>
</body>
</html>

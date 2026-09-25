<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Donation | DRMS</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif; background: #f9f5f4; color: #2c1819; }
    .top-nav { background: linear-gradient(90deg, #3d1419 0%, #6d1f2a 55%, #8b2635 100%); padding: 12px 32px; box-shadow: 0 4px 24px rgba(61,20,25,.25); }
    .top-nav .brand { color: #fff; font-size: 1.3rem; font-weight: 700; text-decoration: none; }
    .top-nav .brand img { width: 34px; height: 36px; margin-right: .5rem; object-fit: contain; }
    .top-nav .nav-links a { color: rgba(255,255,255,.88); margin-left: 22px; font-size: 14px; text-decoration: none; }
    .top-nav .nav-links .btn-nav { background: #fff; color: #3b0b0d; padding: 6px 18px; border-radius: 24px; font-weight: 700; }
    .track-card { max-width: 680px; border: 0; border-radius: 14px; box-shadow: 0 10px 35px rgba(109,31,42,.1); }
    .track-card .card-header { background: #fff; border-bottom: 1px solid #eee; }
    .track-card .card-title { color: #6d1f2a; font-weight: 700; }
    @media (max-width: 700px) {
      .top-nav { padding: 12px 18px; }
      .top-nav .nav-links { flex-wrap: wrap; justify-content: flex-end; }
      .top-nav .nav-links a { margin-left: 12px; }
      .top-nav .nav-links a:first-child { margin-left: 0; }
    }
  </style>
</head>
<body>
  <nav class="top-nav d-flex justify-content-between align-items-center flex-wrap">
    <a href="{{ route('public.home') }}" class="brand d-flex align-items-center">
      <img src="{{ asset('assets/logo/baras_seal_l.png') }}" alt="Municipality of Baras seal">DRMS
    </a>
    <div class="nav-links d-flex align-items-center">
      <a href="{{ route('public.home') }}">Home</a>
      <a href="{{ route('public.evac_centers') }}">Evacuation centers</a>
      <a href="{{ route('donate') }}">Donate</a>
      <a href="{{ route('login') }}" class="btn-nav">Login</a>
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
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $center->name }} — Evacuation Center</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Source Sans Pro', sans-serif; background: #f4f6f9; color: #232323; }
    .top-nav { background: #3b0b0d; padding: 14px 32px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 8px rgba(0,0,0,0.18); }
    .top-nav .brand { color: #fff; font-size: 1.3rem; font-weight: 700; text-decoration: none; }
    .top-nav .nav-links a { color: rgba(255,255,255,0.85); margin-left: 22px; font-size: 14px; text-decoration: none; }
    .top-nav .nav-links .btn-nav { background: #fff; color: #3b0b0d; padding: 6px 18px; border-radius: 24px; font-weight: 700; }
    .top-nav .nav-links a:hover, .top-nav .nav-links .btn-nav:hover { color: #fff; }
    .page-header { padding: 48px 0 24px; }
    .page-header h1 { font-size: 2.25rem; font-weight: 700; margin-bottom: 10px; }
    .page-header p { color: #575757; max-width: 700px; }
    .badge-status-open { background: #28a745; }
    .badge-status-full { background: #dc3545; }
    .badge-status-closed { background: #6c757d; }
    .card { border: none; border-radius: 14px; }
    .card-header { background: #fff; border-bottom: 1px solid #e9ecef; font-weight: 700; }
    .info-list dt { font-weight: 600; }
    .info-list dd { margin-bottom: 12px; }
    footer { text-align: center; padding: 24px 0; color: #6b6b6b; font-size: 13px; }
  </style>
</head>
<body>
<nav class="top-nav">
  <a href="{{ route('public.home') }}" class="brand"><i class="fas fa-shield-alt mr-2"></i>RescuePH</a>
  <div class="nav-links d-flex align-items-center">
    <a href="{{ route('public.home') }}">Home</a>
    <a href="{{ route('public.evac_centers') }}">Evacuation centers</a>
    <a href="{{ route('donate') }}">Donate</a>
    <a href="{{ route('donations.track') }}" class="btn-nav">Track donation</a>
  </div>
</nav>
<main class="container py-5">
  <div class="page-header">
    <h1>{{ $center->name }}</h1>
    <p>Evacuation center details for families and responders. Check the status, capacity, contact information, and requirements before arriving.</p>
  </div>

  <div class="row mb-4">
    <div class="col-md-8">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Center information</h5>
          <dl class="row info-list">
            <dt class="col-sm-4">Barangay</dt>
            <dd class="col-sm-8">{{ $center->barangay }}</dd>
            <dt class="col-sm-4">Address</dt>
            <dd class="col-sm-8">{{ $center->address ?: 'Not specified' }}</dd>
            <dt class="col-sm-4">Status</dt>
            <dd class="col-sm-8">
              <span class="badge badge-status-{{ $center->status == 'active' ? 'open' : ($center->status == 'full' ? 'full' : 'closed') }} text-white">
                {{ $center->status === 'active' ? 'Open' : ucfirst($center->status) }}
              </span>
            </dd>
            <dt class="col-sm-4">Capacity</dt>
            <dd class="col-sm-8">{{ $center->current_occupancy }} / {{ $center->capacity }}</dd>
            <dt class="col-sm-4">Families registered</dt>
            <dd class="col-sm-8">{{ $center->families_registered ?? 0 }}</dd>
            <dt class="col-sm-4">Medical needs flagged</dt>
            <dd class="col-sm-8">{{ $center->medical_needs_count ?? 0 }}</dd>
            <dt class="col-sm-4">On-duty contact</dt>
            <dd class="col-sm-8">{{ $center->contact_person ?: 'Not available' }}</dd>
            <dt class="col-sm-4">Center hotline</dt>
            <dd class="col-sm-8">{{ $center->contact_number ?: 'Not available' }}</dd>
          </dl>
        </div>
      </div>
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Family intake details</h5>
          @if ($center->intake_procedures || $center->required_items)
            @if ($center->intake_procedures)
              <div class="mb-3">
                <h6>Procedure</h6>
                <pre class="bg-light p-3 rounded" style="white-space:pre-wrap;">{{ $center->intake_procedures }}</pre>
              </div>
            @endif
            @if ($center->required_items)
              <div>
                <h6>Required items</h6>
                <pre class="bg-light p-3 rounded" style="white-space:pre-wrap;">{{ $center->required_items }}</pre>
              </div>
            @endif
          @else
            <p class="text-muted mb-0">No intake instructions are available yet for this center. Please contact the center before traveling.</p>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header">Get ready</div>
        <div class="card-body">
          <p class="mb-2"><strong>Before you go:</strong></p>
          <ul class="pl-3" style="color:#575757;">
            <li>Confirm the center is open and accepting families.</li>
            <li>Bring valid ID for the family head.</li>
            <li>Prepare medicines and special needs supplies.</li>
            <li>Call the center hotline if you need directions.</li>
          </ul>
        </div>
      </div>
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <a href="{{ route('public.evac_centers') }}" class="btn btn-outline-dark btn-block mb-2">
            <i class="fas fa-arrow-left mr-1"></i> Back to centers
          </a>
          <a href="{{ route('donate') }}" class="btn btn-primary btn-block">
            Support relief operations
          </a>
        </div>
      </div>
    </div>
  </div>
</main>
<footer>
  &copy; {{ date('Y') }} RescuePH — Municipality of Baras, Rizal
</footer>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

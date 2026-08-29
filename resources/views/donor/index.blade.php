@extends('layouts.app')

@section('title', 'Donor Portal')
@section('page-title', 'Donor Portal')

@section('breadcrumb')
  <li class="breadcrumb-item active">Donor Portal</li>
@endsection

@section('content')
  @php
    $totalDonations = $donations->count();
    $receivedDonations = $donations->whereIn('status', ['received', 'distributed'])->count();
    $pendingDonations = $donations->where('status', 'pending')->count();
    $inKindDonations = $donations->where('type', 'in-kind')->count();
  @endphp

  <div class="row mb-3">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner"><h3>{{ $totalDonations }}</h3><p>Total Donations</p></div>
        <div class="icon"><i class="fas fa-heart"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner"><h3>{{ $receivedDonations }}</h3><p>Received or Distributed</p></div>
        <div class="icon"><i class="fas fa-check-circle"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner"><h3>{{ $pendingDonations }}</h3><p>Pending</p></div>
        <div class="icon"><i class="fas fa-clock"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner"><h3>{{ $inKindDonations }}</h3><p>In-Kind Donations</p></div>
        <div class="icon"><i class="fas fa-box-open"></i></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Welcome, {{ $user->name }}</h3>
        </div>
        <div class="card-body">
          <h4 class="mb-2">Support RescuePH disaster response.</h4>
          <p class="text-muted mb-4">Submit a monetary or in-kind donation and monitor its progress from this portal.</p>
          <a href="{{ route('donate') }}" class="btn btn-danger">
            Make a New Donation
          </a>
          <a href="{{ route('donations.track') }}" class="btn btn-outline-secondary ml-2">
            <i class="fas fa-search mr-1"></i> Track a Donation
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-list mr-2"></i>My Donation Records</h3>
        </div>
        <div class="card-body p-0">
          @if($donations->isEmpty())
            <div class="p-4 text-center text-muted">
              <i class="fas fa-inbox fa-2x mb-2"></i>
              <p class="mb-3">No donations are linked to this email address yet.</p>
              <a href="{{ route('donate') }}" class="btn btn-sm btn-danger">Make your first donation</a>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead><tr><th>Tracking Code</th><th>Type</th><th>Details</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                  @foreach($donations->take(10) as $donation)
                    <tr>
                      <td><span class="badge badge-dark">{{ $donation->tracking_code }}</span></td>
                      <td>{{ $donation->type === 'in-kind' ? 'In-Kind' : 'Monetary' }}</td>
                      <td>{{ $donation->type === 'in-kind' ? Str::limit($donation->items_description, 35) : 'PHP ' . number_format($donation->amount, 2) }}</td>
                      <td>
                        @if($donation->status === 'pending')<span class="badge badge-warning">Pending</span>
                        @elseif($donation->status === 'received')<span class="badge badge-success">Received</span>
                        @else<span class="badge badge-primary">Distributed</span>@endif
                      </td>
                      <td>{{ $donation->created_at->format('M d, Y') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Donor Details</h3>
        </div>
        <div class="card-body">
          <p><strong>Name</strong><br>{{ $user->name }}</p>
          <p><strong>Email</strong><br>{{ $user->email }}</p>
          <p><strong>Role</strong><br>{{ $user->role->name ?? ucfirst(str_replace('_', ' ', $user->role->slug)) }}</p>
        </div>
      </div>
    </div>
  </div>
@endsection

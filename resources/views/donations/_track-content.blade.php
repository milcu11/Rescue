    <div class="text-center mb-4">
      <h1 class="font-weight-bold">Track your donation</h1>
      <p class="text-muted">Enter the tracking code from your donation receipt.</p>
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

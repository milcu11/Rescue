@extends('layouts.app')

@section('title', 'Donations')
@section('page-title', 'Donation Tracking')

@section('breadcrumb')
  <li class="breadcrumb-item active">Donations</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
  <div class="col-6 col-lg-3">
    <div class="small-box small-box-purple text-white">
      <div class="inner">
        <h3>{{ $summary['total'] }}</h3>
        <p>Total Donations</p>
      </div>
      <div class="icon">
        <i class="fas fa-heart"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Overview <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-warning text-white">
      <div class="inner">
        <h3>{{ $summary['pending'] }}</h3>
        <p>Pending</p>
      </div>
      <div class="icon">
        <i class="fas fa-hourglass-half"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Pending items <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-success text-white">
      <div class="inner">
        <h3>{{ $summary['received'] }}</h3>
        <p>Received</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Received <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="small-box bg-danger text-white">
      <div class="inner">
        <h3>{{ $summary['distributed'] }}</h3>
        <p>Distributed</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>
      <a href="{{ route('donations.index') }}" class="small-box-footer">
        Distributed <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">
      Donation Records
    </h3>
    <div class="d-flex flex-wrap align-items-center">
      <a href="{{ route('donations.track') }}" class="btn btn-sm btn-outline-secondary mr-2 mb-2">
        <i class="bi bi-search me-1"></i>Track Donation
      </a>
      @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
        <a href="{{ route('donations.create') }}" class="btn btn-sm btn-danger mb-2">
          <i class="bi bi-plus-lg me-1"></i>Record Donation
        </a>
      @endif
    </div>
  </div>
  <div class="card-body">
    <table id="donationsTable" class="table table-bordered table-hover table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Tracking Code</th>
          <th>Donor</th>
          <th>Type</th>
          <th>Details</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="donationsTableBody">
        @forelse($donations as $donation)
        <tr>
          <td>
            <span class="badge bg-dark font-monospace">{{ $donation->tracking_code }}</span>
          </td>
          <td>
            <div class="fw-semibold">{{ $donation->donor_name }}</div>
            @if($donation->donor_contact)
              <small class="text-muted">{{ $donation->donor_contact }}</small>
            @endif
          </td>
          <td>
            @if($donation->type === 'monetary')
              <span class="badge bg-success">Monetary</span>
            @else
              <span class="badge bg-info text-dark">In-Kind</span>
            @endif
          </td>
          <td>
            @if($donation->type === 'monetary')
              ₱{{ number_format($donation->amount, 2) }}
            @else
              {{ Str::limit($donation->items_description, 50) }}
            @endif
          </td>
          <td>
            @if($donation->status === 'pending')
              <span class="badge bg-warning text-dark">Pending</span>
            @elseif($donation->status === 'received')
              <span class="badge bg-success">Received</span>
            @else
              <span class="badge bg-primary">Distributed</span>
            @endif
          </td>
          <td>{{ $donation->created_at->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-info">
              <i class="bi bi-eye"></i>
            </a>
            @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
              <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this donation record?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
              No donations recorded yet.
              @if(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']))
                <a href="{{ route('donations.create') }}">Record the first one.</a>
              @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    var $donTable = $('#donationsTable');
    var externalEndpoint = 'https://drvms.freedev.app/api/v1/public/donations?limit=100';
    var localEndpoint = '{{ url('/api/v1/public/donations') }}?limit=100';
    var donationBaseUrl = '{{ url('/donations') }}';
    var canManage = @json(!in_array(Auth::user()->role->slug, ['lgu_staff', 'warehouse_staff']));

    function fetchJson(url) {
      return fetch(url, {
        headers: { 'Accept': 'application/json' }
      }).then(function (response) {
        if (!response.ok) {
          throw new Error('Request failed: ' + response.status);
        }
        return response.json();
      });
    }

    function formatDate(value) {
      if (!value) return '—';
      var date = new Date(value);
      if (Number.isNaN(date.getTime())) return value;
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function statusBadge(status) {
      if (status === 'pending') {
        return '<span class="badge bg-warning text-dark">Pending</span>';
      }
      if (status === 'received') {
        return '<span class="badge bg-success">Received</span>';
      }
      return '<span class="badge bg-primary">Distributed</span>';
    }

    function typeBadge(type) {
      if (type === 'monetary') {
        return '<span class="badge bg-success">Monetary</span>';
      }
      return '<span class="badge bg-info text-dark">In-Kind</span>';
    }

    function renderDonationRows(rows) {
      var $body = $('#donationsTableBody');
      if (!rows.length) {
        $body.html('<tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-2"></i>No donation records available.</td></tr>');
        return;
      }

      var html = rows.map(function (item) {
        var donorName = item.donor_name || 'Unknown Donor';
        var donorContact = item.donor_contact ? '<small class="text-muted">' + item.donor_contact + '</small>' : '';
        var detail = item.type === 'monetary'
          ? '₱' + Number(item.amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
          : (item.items_description || '—');

        var actionButtons = '<a href="' + donationBaseUrl + '/' + item.id + '" class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></a>';
        if (canManage) {
          actionButtons += ' <a href="' + donationBaseUrl + '/' + item.id + '/edit" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>';
        }

        return '<tr>' +
          '<td><span class="badge bg-dark font-monospace">' + (item.tracking_code || '—') + '</span></td>' +
          '<td><div class="fw-semibold">' + donorName + '</div>' + donorContact + '</td>' +
          '<td>' + typeBadge(item.type) + '</td>' +
          '<td>' + detail + '</td>' +
          '<td>' + statusBadge(item.status) + '</td>' +
          '<td>' + formatDate(item.created_at) + '</td>' +
          '<td>' +
            actionButtons +
          '</td>' +
          '</tr>';
      }).join('');

      $body.html(html);
    }

    fetchJson(localEndpoint)
      .then(function (payload) {
        var rows = Array.isArray(payload && payload.data) ? payload.data : [];
        renderDonationRows(rows);
        if (window.safeInit) {
          safeInit($donTable, {
            pageLength: 25,
            order: [[5, 'desc']],
            columnDefs: [{ orderable: false, targets: [-1] }]
          });
        }
      })
      .catch(function () {
        return fetchJson(externalEndpoint)
          .then(function (payload) {
            var rows = Array.isArray(payload && payload.data) ? payload.data : [];
            renderDonationRows(rows);
            if (window.safeInit) {
              safeInit($donTable, {
                pageLength: 25,
                order: [[5, 'desc']],
                columnDefs: [{ orderable: false, targets: [-1] }]
              });
            }
          })
          .catch(function () {
            if (window.safeInit) {
              safeInit($donTable, {
                pageLength: 25,
                order: [[5, 'desc']],
                columnDefs: [{ orderable: false, targets: [-1] }]
              });
            }
          });
      });
  });
</script>
@endpush

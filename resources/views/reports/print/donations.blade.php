@extends('reports.print.layout')
@section('title', 'Donations Report')

@section('content')

<div class="summary">
  <div class="summary-box">
    <div class="num">{{ $donations->count() }}</div>
    <div class="lbl">Total</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $donations->where('status','pending')->count() }}</div>
    <div class="lbl">Pending</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $donations->where('status','received')->count() }}</div>
    <div class="lbl">Received</div>
  </div>
  <div class="summary-box">
    <div class="num">₱{{ number_format($donations->where('type','monetary')->sum('amount'),2) }}</div>
    <div class="lbl">Total Monetary</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>Tracking Code</th>
      <th>Donor</th>
      <th>Type</th>
      <th>Details</th>
      <th>Status</th>
      <th>Received By</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    @forelse($donations as $d)
    <tr>
      <td><strong>{{ $d->tracking_code }}</strong></td>
      <td>{{ $d->donor_name }}</td>
      <td>{{ ucfirst($d->type) }}</td>
      <td>
        @if($d->type === 'monetary')
          ₱{{ number_format($d->amount,2) }}
        @else
          {{ Illuminate\Support\Str::limit($d->items_description, 50) }}
        @endif
      </td>
      <td>
        <span class="badge badge-{{ $d->status === 'received' ? 'success' : ($d->status === 'pending' ? 'warning' : 'secondary') }}">
          {{ ucfirst($d->status) }}
        </span>
      </td>
      <td>{{ $d->received_by ?? '—' }}</td>
      <td>{{ $d->created_at->format('M d, Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:#aaa;">No donations.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection

@extends('reports.print.layout')
@section('title', 'Evacuation Centers Report')

@section('content')

<div class="summary">
  <div class="summary-box">
    <div class="num">{{ $centers->count() }}</div>
    <div class="lbl">Total Centers</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $centers->where('status','active')->count() }}</div>
    <div class="lbl">Active</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $centers->sum('current_occupancy') }}</div>
    <div class="lbl">Total Evacuees</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $centers->sum('capacity') }}</div>
    <div class="lbl">Total Capacity</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Center Name</th>
      <th>Barangay</th>
      <th>Capacity</th>
      <th>Occupancy</th>
      <th>%</th>
      <th>Status</th>
      <th>Contact Person</th>
    </tr>
  </thead>
  <tbody>
    @forelse($centers as $center)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $center->name }}</td>
      <td>{{ $center->barangay }}</td>
      <td>{{ number_format($center->capacity) }}</td>
      <td>{{ number_format($center->current_occupancy) }}</td>
      <td>{{ $center->occupancy_percent }}%</td>
      <td>
        <span class="badge badge-{{ $center->status === 'active' ? 'success' : ($center->status === 'full' ? 'warning' : 'secondary') }}">
          {{ ucfirst($center->status) }}
        </span>
      </td>
      <td>{{ $center->contact_person ?? '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;color:#aaa;">No centers.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection

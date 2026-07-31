@extends('reports.print.layout')
@section('title', 'Relief Distributions Report')

@section('content')

<div class="summary">
  <div class="summary-box">
    <div class="num">{{ $distributions->count() }}</div>
    <div class="lbl">Distributions</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ number_format($distributions->sum('beneficiaries_count')) }}</div>
    <div class="lbl">Beneficiaries</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $distributions->pluck('evacuation_center_id')->unique()->count() }}</div>
    <div class="lbl">Centers Served</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $distributions->pluck('relief_operation_id')->unique()->count() }}</div>
    <div class="lbl">Operations</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Operation</th>
      <th>Center</th>
      <th>Item</th>
      <th>Qty</th>
      <th>Beneficiaries</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    @forelse($distributions as $d)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $d->operation->name ?? '—' }}</td>
      <td>{{ $d->center->name ?? '—' }}</td>
      <td>{{ $d->item->name ?? '—' }}</td>
      <td>{{ number_format($d->quantity_distributed) }} {{ $d->item->unit ?? '' }}</td>
      <td>{{ number_format($d->beneficiaries_count) }}</td>
      <td>{{ $d->distributed_at->format('M d, Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:#aaa;">No distributions.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection

@extends('reports.print.layout')
@section('title', 'Inventory Report')

@section('content')

<div class="summary">
  <div class="summary-box">
    <div class="num">{{ $items->count() }}</div>
    <div class="lbl">Total Items</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $items->where('status','available')->count() }}</div>
    <div class="lbl">Available</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $items->where('status','low_stock')->count() }}</div>
    <div class="lbl">Low Stock</div>
  </div>
  <div class="summary-box">
    <div class="num">{{ $items->where('status','depleted')->count() }}</div>
    <div class="lbl">Depleted</div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Category</th>
      <th>Quantity</th>
      <th>Unit</th>
      <th>Min. Threshold</th>
      <th>Location</th>
      <th>Status</th>
      <th>Date Added</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $item)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $item->name }}</td>
      <td>{{ ucfirst($item->category) }}</td>
      <td>{{ number_format($item->quantity) }}</td>
      <td>{{ $item->unit }}</td>
      <td>{{ number_format($item->minimum_threshold) }}</td>
      <td>{{ $item->location ?? '—' }}</td>
      <td>
        <span class="badge badge-{{ $item->status === 'available' ? 'success' : ($item->status === 'low_stock' ? 'warning' : 'danger') }}">
          {{ ucfirst(str_replace('_',' ',$item->status)) }}
        </span>
      </td>
      <td>{{ $item->created_at->format('M d, Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;color:#aaa;">No items.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection

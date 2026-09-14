<!DOCTYPE html>
<html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:10px;color:#222}h1{font-size:16px;color:#3b0b0d;margin-bottom:2px}p{font-size:9px;color:#666}table{width:100%;border-collapse:collapse;margin-top:12px}th{background:#3b0b0d;color:#fff;padding:5px;text-align:left}td{padding:4px 5px;border-bottom:1px solid #eee}tr:nth-child(even) td{background:#f9f9f9}
</style></head><body>
<h1>RescuePH - Stock Movement Report</h1>
<p>Coverage: {{ $filters['from'] ?? 'Beginning' }} to {{ $filters['to'] ?? 'Present' }} | Generated: {{ now()->format('F d, Y h:i A') }} by {{ Auth::user()->name }} | Filters: {{ collect($filters ?? [])->filter()->map(fn($value, $key) => ucfirst($key).'='.$value)->join(', ') ?: 'None' }} | Total: {{ $movements->count() }}</p>
<table><thead><tr><th>Reference</th><th>Item</th><th>Type</th><th>Quantity</th><th>Before</th><th>After</th><th>Recorded By</th><th>Occurred At</th><th>Notes</th></tr></thead><tbody>
@forelse($movements as $movement)<tr><td>{{ $movement->reference }}</td><td>{{ $movement->item?->name ?? '—' }}</td><td>{{ ucfirst(str_replace('_',' ',$movement->type)) }}</td><td>{{ $movement->quantity }}</td><td>{{ $movement->quantity_before }}</td><td>{{ $movement->quantity_after }}</td><td>{{ $movement->user?->name ?? 'System' }}</td><td>{{ $movement->occurred_at?->format('M d, Y h:i A') ?? '—' }}</td><td>{{ $movement->notes ?? '—' }}</td></tr>@empty<tr><td colspan="9">No stock movements found.</td></tr>@endforelse
</tbody></table></body></html>

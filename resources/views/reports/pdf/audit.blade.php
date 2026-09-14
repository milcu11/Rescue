<!DOCTYPE html>
<html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:10px;color:#222}h1{font-size:16px;color:#3b0b0d;margin-bottom:2px}p{font-size:9px;color:#666}table{width:100%;border-collapse:collapse;margin-top:12px}th{background:#3b0b0d;color:#fff;padding:5px;text-align:left}td{padding:4px 5px;border-bottom:1px solid #eee}tr:nth-child(even) td{background:#f9f9f9}
</style></head><body>
<h1>RescuePH - Audit Trail Report</h1>
<p>Coverage: {{ $filters['from'] ?? 'Beginning' }} to {{ $filters['to'] ?? 'Present' }} | Generated: {{ now()->format('F d, Y h:i A') }} by {{ Auth::user()->name }} | Filters: {{ collect($filters ?? [])->filter()->map(fn($value, $key) => ucfirst($key).'='.$value)->join(', ') ?: 'None' }} | Total: {{ $logs->count() }}</p>
<table><thead><tr><th>Date</th><th>User</th><th>Role</th><th>Action</th><th>Module</th><th>Record</th><th>IP Address</th><th>Notes</th></tr></thead><tbody>
@forelse($logs as $log)<tr><td>{{ $log->created_at?->format('M d, Y h:i A') ?? '—' }}</td><td>{{ $log->user_name }}</td><td>{{ $log->user_role }}</td><td>{{ ucfirst($log->action) }}</td><td>{{ ucfirst(str_replace('_',' ',$log->module)) }}</td><td>{{ $log->record_label ?? '—' }}</td><td>{{ $log->ip_address ?? '—' }}</td><td>{{ $log->notes ?? '—' }}</td></tr>@empty<tr><td colspan="8">No audit records found.</td></tr>@endforelse
</tbody></table></body></html>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; color: #7B1113; margin-bottom: 2px; }
    p { font-size: 10px; color: #888; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #7B1113; color: #fff; padding: 5px 6px; font-size: 10px; text-align: left; }
    td { padding: 4px 6px; border-bottom: 1px solid #eee; font-size: 10px; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .footer { margin-top: 16px; font-size: 9px; color: #aaa; text-align: center; }
  </style>
</head>
<body>
  <h1>RescuePH — Relief Distributions Report</h1>
  <p>Generated: {{ now()->format('F d, Y h:i A') }} | Total distributions: {{ $distributions->count() }}</p>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Operation</th><th>Center</th><th>Item</th><th>Qty</th><th>Beneficiaries</th><th>Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($distributions as $d)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->operation?->name ?? '—' }}</td>
        <td>{{ $d->center?->name ?? '—' }}</td>
        <td>{{ $d->item?->name ?? '—' }}</td>
        <td>{{ number_format($d->quantity_distributed) }} {{ $d->item?->unit ?? '' }}</td>
        <td>{{ number_format($d->beneficiaries_count) }}</td>
        <td>{{ $d->distributed_at?->format('M d, Y') ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="footer">RescuePH &copy; {{ date('Y') }} — For thesis/capstone purposes only</div>
</body>
</html>

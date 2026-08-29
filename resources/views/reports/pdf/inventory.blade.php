<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; color: #3b0b0d; margin-bottom: 2px; }
    p { font-size: 10px; color: #888; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #3b0b0d; color: #fff; padding: 5px 6px; font-size: 10px; text-align: left; }
    td { padding: 4px 6px; border-bottom: 1px solid #eee; font-size: 10px; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .footer { margin-top: 16px; font-size: 9px; color: #aaa; text-align: center; }
  </style>
</head>
<body>
  <h1>RescuePH — Inventory Report</h1>
  <p>Generated: {{ now()->format('F d, Y h:i A') }} | Total: {{ $items->count() }} items</p>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Category</th><th>Qty</th><th>Unit</th><th>Status</th><th>Location</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ ucfirst($item->category) }}</td>
        <td>{{ number_format($item->quantity) }}</td>
        <td>{{ $item->unit }}</td>
        <td>{{ ucfirst(str_replace('_',' ',$item->status)) }}</td>
        <td>{{ $item->warehouse ?? '—' }}</td>
        <td>{{ $item->location ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="footer">RescuePH &copy; {{ date('Y') }} — For thesis/capstone purposes only</div>
</body>
</html>

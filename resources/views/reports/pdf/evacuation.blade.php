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
  <h1>RescuePH — Evacuation Report</h1>
  <p>Generated: {{ now()->format('F d, Y h:i A') }} | Total centers: {{ $centers->count() }}</p>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Center Name</th><th>Barangay</th><th>Capacity</th><th>Occupancy</th><th>%</th><th>Status</th><th>Contact</th>
      </tr>
    </thead>
    <tbody>
      @foreach($centers as $center)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $center->name }}</td>
        <td>{{ $center->barangay }}</td>
        <td>{{ number_format($center->capacity) }}</td>
        <td>{{ number_format($center->current_occupancy) }}</td>
        <td>{{ $center->occupancy_percent }}%</td>
        <td>{{ ucfirst($center->status) }}</td>
        <td>{{ $center->contact_person ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="footer">RescuePH &copy; {{ date('Y') }} — For thesis/capstone purposes only</div>
</body>
</html>

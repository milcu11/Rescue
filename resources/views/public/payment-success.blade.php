<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Confirmed</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Source Sans Pro', sans-serif; color: #333; background: #f7f7f7; }
    .success-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .success-card {
      max-width: 600px;
      width: 100%;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,.08);
      padding: 60px 40px;
      text-align: center;
    }
    .success-icon {
      font-size: 4rem;
      color: #28a745;
      margin-bottom: 20px;
    }
    .success-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #222;
      margin-bottom: 12px;
    }
    .success-subtitle {
      font-size: 1rem;
      color: #888;
      margin-bottom: 32px;
    }
    .info-table {
      text-align: left;
      margin-bottom: 32px;
    }
    .info-table tr {
      border-bottom: 1px solid #eee;
    }
    .info-table th {
      color: #888;
      font-weight: 600;
      font-size: 0.85rem;
      padding: 12px 0;
      width: 140px;
    }
    .info-table td {
      padding: 12px 0;
      color: #333;
      font-weight: 500;
    }
    .info-table code {
      background: #f0f0f0;
      padding: 2px 6px;
      border-radius: 4px;
      font-family: monospace;
      font-size: 0.9rem;
    }
    .callout {
      background: #f0f8ff;
      border-left: 4px solid #28a745;
      padding: 16px;
      border-radius: 6px;
      margin-bottom: 32px;
      color: #2c5f2d;
    }
    .btn-primary-custom {
      background: #3b0b0d;
      color: #fff;
      border: 0;
      padding: 10px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
      transition: all .2s;
    }
    .btn-primary-custom:hover {
      background: #4b0f11;
      color: #fff;
      text-decoration: none;
    }
    .btn-outline-custom {
      background: transparent;
      color: #3b0b0d;
      border: 2px solid #3b0b0d;
      padding: 8px 22px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
      transition: all .2s;
      margin-left: 8px;
    }
    .btn-outline-custom:hover {
      background: #3b0b0d;
      color: #fff;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="success-container">
  <div class="success-card">
    <div class="success-icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <div class="success-title">Donation Confirmed!</div>
    <div class="success-subtitle">Your payment has been received and processed.</div>

    <table class="info-table">
      <tr>
        <th>Tracking Code</th>
        <td><code>{{ $donation->tracking_code }}</code></td>
      </tr>
      <tr>
        <th>Amount</th>
        <td class="text-success font-weight-bold">₱{{ number_format($donation->amount, 2) }}</td>
      </tr>
      <tr>
        <th>Payment Method</th>
        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Unknown')) }}</td>
      </tr>
      <tr>
        <th>Donor Name</th>
        <td>{{ $donation->donor_name }}</td>
      </tr>
      <tr>
        <th>Date</th>
        <td>{{ now()->format('M d, Y h:i A') }}</td>
      </tr>
    </table>

    <div class="callout">
      <strong>Thank you for your generous donation to RescuePH!</strong>
      <div style="margin-top: 8px; font-size: 0.9rem;">
        Your donation will help support relief operations in Baras, Rizal. You can track the impact of your contribution anytime using your tracking code.
      </div>
    </div>

    <div>
      <a href="{{ route('donations.track') }}?code={{ $donation->tracking_code }}" class="btn-primary-custom">
        <i class="fas fa-search mr-2"></i>Track Your Donation
      </a>
      <a href="{{ route('public.home') }}" class="btn-outline-custom">
        <i class="fas fa-home mr-2"></i>Back to Home
      </a>
    </div>
  </div>
</div>
</body>
</html>

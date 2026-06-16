<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Report Status Updated</title>
    <style>
      body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:#f6f6f6; margin:0; padding:0; }
      .email-wrapper { width:100%; background:#f6f6f6; padding:20px 0; }
      .email-body { max-width:600px; margin:0 auto; background:#ffffff; border-radius:6px; overflow:hidden; }
      .email-header { background:#0d6efd; color:#ffffff; padding:20px; text-align:left; }
      .email-content { padding:24px; color:#333333; }
      .button { display:inline-block; background:#0d6efd; color:#fff; padding:10px 16px; border-radius:4px; text-decoration:none; }
      .meta { font-size:13px; color:#666666; margin-top:8px; }
      .footer { padding:16px; text-align:center; font-size:12px; color:#888888; }
    </style>
  </head>
  <body>
    <div class="email-wrapper">
      <div class="email-body">
        <div class="email-header">
          <h2 style="margin:0;font-size:18px;">Report Status Updated</h2>
        </div>
        <div class="email-content">
          <p>Hello {{ $report->user->name ?? 'Citizen' }},</p>

          <p>We wanted to let you know the status of your report has changed.</p>

          <p><strong>Tracking:</strong> {{ $report->tracking_code ?? $report->id }}<br>
          <strong>New status:</strong> {{ $report->status }}<br>
          <strong>Category:</strong> {{ optional($report->crimeCategory)->name ?? '—' }}</p>

          @if($report->description)
          <p><strong>Summary:</strong><br>{{ \Illuminate\Support\Str::limit($report->description, 350) }}</p>
          @endif

          <p class="meta">If you have questions, reply to this email or contact the local office.</p>

          <p style="margin-top:18px"><a class="button" href="{{ config('app.url') }}">View your report</a></p>
        </div>
        <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
      </div>
    </div>
  </body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Status Updated</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 15px rgba(0, 0, 0, 0.03);
        }
        .header {
            background: linear-gradient(135deg, #0f5132 0%, #198754 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            color: #334155;
        }
        .salutation {
            font-size: 18px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 16px;
            color: #0f172a;
        }
        .intro-text {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .status-card {
            background-color: #f1f5f9;
            border-left: 4px solid #198754;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .status-label {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
        }
        .status-value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .badge-submitted { background-color: #cfe2ff; color: #084298; }
        .badge-under-review { background-color: #fff3cd; color: #664d03; }
        .badge-assigned { background-color: #e2d9ff; color: #3b2a82; }
        .badge-resolved { background-color: #d1e7dd; color: #0f5132; }
        .badge-closed { background-color: #e2e8f0; color: #475569; }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th, .details-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            font-size: 14px;
        }
        .details-table th {
            color: #64748b;
            font-weight: 500;
            width: 30%;
        }
        .details-table td {
            color: #0f172a;
            font-weight: 600;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #198754;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(25, 135, 84, 0.15);
            transition: background-color 0.2s;
        }
        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 13px;
        }
        .footer p {
            margin: 6px 0;
        }
        .footer a {
            color: #64748b;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Report Status Updated</h1>
                <p>NEMA E-Crime Reporting & Management System</p>
            </div>
            <div class="content">
                <p class="salutation">Hello {{ $report->user ? $report->user->name : 'Citizen' }},</p>
                <p class="intro-text">
                    The status of your environmental crime report has been updated by NEMA. Please find the details of the update below.
                </p>

                <div class="status-card">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td class="status-label" style="padding: 0 0 4px 0; border: none;">Report Reference</td>
                            <td class="status-label" style="padding: 0 0 4px 0; border: none; text-align: right;">Current Status</td>
                        </tr>
                        <tr>
                            <td class="status-value" style="padding: 0; border: none;">#{{ $report->id }}</td>
                            <td style="padding: 0; border: none; text-align: right;">
                                @php
                                    $statusClass = match($report->status) {
                                        'Submitted' => 'badge-submitted',
                                        'Under Review' => 'badge-under-review',
                                        'Assigned' => 'badge-assigned',
                                        'Resolved' => 'badge-resolved',
                                        'Closed' => 'badge-closed',
                                        default => 'badge-submitted'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $report->status }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">Case Information</h3>
                <table class="details-table">
                    <tr>
                        <th>Category</th>
                        <td>{{ $report->crimeCategory->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Priority</th>
                        <td>{{ $report->priority }}</td>
                    </tr>
                    @if($report->location_address)
                    <tr>
                        <th>Location</th>
                        <td>{{ $report->location_address }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $report->updated_at->format('M d, Y \a\t h:i A') }}</td>
                    </tr>
                </table>

                <div class="btn-container">
                    <a href="{{ route('citizen.dashboard') }}" class="btn">View Case Dashboard</a>
                </div>

                <p class="intro-text" style="margin-top: 30px; margin-bottom: 0;">
                    Thank you for partnering with NEMA to protect our environment. If you have any further questions, please contact our help desk.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} National Environment Management Authority (NEMA), Uganda.</p>
                <p>Plot 17, 19 & 21 Jinja Road, Kampala. P.O. Box 22255 Kampala, Uganda.</p>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

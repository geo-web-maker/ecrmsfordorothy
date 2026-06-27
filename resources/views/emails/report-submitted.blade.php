<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Received</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #F3F5EA; margin: 0; padding: 24px; color: #1F3318; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(63,107,42,0.12); }
        .header { background: linear-gradient(135deg, #3F6B2A 0%, #5E8B3D 100%); color: #fff; padding: 32px 28px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 8px 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 28px; line-height: 1.6; font-size: 15px; }
        .code-box { background: #EAF1DD; border: 2px solid #5E8B3D; border-radius: 10px; padding: 16px; text-align: center; margin: 20px 0; }
        .code { font-family: monospace; font-size: 22px; font-weight: 700; color: #3F6B2A; letter-spacing: 2px; }
        .btn { display: inline-block; background: #3F6B2A; color: #fff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .footer { padding: 20px 28px; background: #FAFBF7; font-size: 12px; color: #7B8F69; text-align: center; border-top: 1px solid rgba(94,139,61,0.15); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Report Received</h1>
            <p>NEMA Environmental Crime Reporting &amp; Monitoring System</p>
        </div>
        <div class="content">
            @php
                $name = $report->stuff?->whistleblowerProfile?->first_name ?? 'Citizen';
            @endphp
            <p>Hello {{ $name }},</p>
            <p>Thank you for submitting an environmental crime report to NEMA. Your case has been logged and will be reviewed by our team.</p>

            <div class="code-box">
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #5E8B3D; font-weight: 700; margin-bottom: 8px;">Your Tracking Code</div>
                <div class="code">{{ $report->tracking_code }}</div>
            </div>

            <p><strong>Category:</strong> {{ $report->crime?->category_name ?? '—' }}<br>
            <strong>Status:</strong> {{ $report->status }}</p>

            <p>Keep this code safe — you will need it to track your case progress.</p>
            <a href="{{ route('report.track') }}" class="btn">Track Your Case</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NEMA Uganda. This is an automated message.</p>
        </div>
    </div>
</body>
</html>

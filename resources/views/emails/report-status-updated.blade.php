<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Status Updated</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #F3F5EA; margin: 0; padding: 24px; color: #1F3318; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(63,107,42,0.12); }
        .header { background: linear-gradient(135deg, #3F6B2A 0%, #5E8B3D 100%); color: #fff; padding: 32px 28px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 8px 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 28px; line-height: 1.6; font-size: 15px; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 700; background: #EAF1DD; color: #3F6B2A; }
        .details { background: #FAFBF7; border-radius: 10px; padding: 16px 20px; margin: 20px 0; border: 1px solid rgba(94,139,61,0.15); }
        .details p { margin: 8px 0; }
        .btn { display: inline-block; background: #3F6B2A; color: #fff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .footer { padding: 20px 28px; background: #FAFBF7; font-size: 12px; color: #7B8F69; text-align: center; border-top: 1px solid rgba(94,139,61,0.15); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Report Status Updated</h1>
            <p>NEMA Environmental Crime Reporting &amp; Monitoring System</p>
        </div>
        <div class="content">
            @php
                $name = $report->stuff?->whistleblowerProfile?->first_name ?? 'Citizen';
            @endphp
            <p>Hello {{ $name }},</p>
            <p>The status of your environmental crime report has been updated.</p>

            <div class="details">
                <p><strong>Tracking code:</strong> {{ $report->tracking_code ?? $report->report_id }}</p>
                <p><strong>New status:</strong> <span class="badge">{{ $report->status }}</span></p>
                <p><strong>Category:</strong> {{ $report->crime?->category_name ?? '—' }}</p>
                @if ($report->description)
                    <p><strong>Summary:</strong> {{ \Illuminate\Support\Str::limit($report->description, 200) }}</p>
                @endif
            </div>

            <a href="{{ route('report.track') }}" class="btn">Track Your Case</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NEMA Uganda. This is an automated message — please do not reply.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to NEMA eCRMS</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #F3F5EA; margin: 0; padding: 24px; color: #1F3318; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(63,107,42,0.12); }
        .header { background: linear-gradient(135deg, #012d1d 0%, #1b4332 100%); color: #fff; padding: 32px 28px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 8px 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 28px; line-height: 1.6; font-size: 15px; }
        .btn { display: inline-block; background: #3F6B2A; color: #fff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .list { margin: 16px 0; padding-left: 20px; }
        .list li { margin-bottom: 8px; }
        .footer { padding: 20px 28px; background: #FAFBF7; font-size: 12px; color: #7B8F69; text-align: center; border-top: 1px solid rgba(94,139,61,0.15); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to NEMA eCRMS</h1>
            <p>Environmental Crime Reporting &amp; Monitoring System</p>
        </div>
        <div class="content">
            @php
                $name = $user->whistleblowerProfile?->first_name ?? 'Citizen';
            @endphp
            <p>Hello {{ $name }},</p>
            <p>Your citizen account has been created successfully. You can now report environmental crimes, submit evidence, and track case progress through the portal.</p>

            <ul class="list">
                <li>Submit reports with photos, coordinates, and descriptions</li>
                <li>Track case status with your tracking code</li>
                <li>Receive updates as your report is reviewed</li>
            </ul>

            <p>Sign in with the email address you registered:</p>
            <p><strong>{{ $user->email }}</strong></p>

            <a href="{{ route('login') }}" class="btn">Sign in to your account</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} National Environment Management Authority (NEMA). This is an automated message.</p>
        </div>
    </div>
</body>
</html>

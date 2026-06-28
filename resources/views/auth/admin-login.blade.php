<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NEMA eCRMS — Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/admin-login.css', 'resources/js/admin-login.js'])
</head>
<body class="admin-login-page">

<div id="cur"></div>
<div id="cur-ring"></div>

<div class="page">

    {{-- Left panel --}}
    <div class="left">
        <div class="left-canvas">
            <div class="blob blob1"></div>
            <div class="blob blob2"></div>
            <div class="blob blob3"></div>
            <div class="left-grid"></div>
            <canvas id="particles"></canvas>
        </div>

        <div class="left-top">
            <div class="brand">
                <div class="brand-name">NEMA <span>eCRMS</span></div>
                <div class="brand-pip-row">
                    <div class="brand-pip"></div>
                    <div class="secure-badge">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="rgba(34,197,94,.8)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Secure Encrypted Session</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="left-mid">
            <h2 class="left-headline">
                Environmental crime<br>
                <em>reporting and monitoring</em><br>
                system
            </h2>
            <p class="left-sub">Authorised personnel only. All sessions are monitored, logged, and end-to-end encrypted for institutional security.</p>

            <div class="left-stats">
                <div class="l-stat">
                    <div class="l-stat-num">{{ number_format($totalReports) }}</div>
                    <div class="l-stat-label">Reports Filed</div>
                </div>
                <div class="l-stat">
                    <div class="l-stat-num">{{ $resolvedPercent }}<b>%</b></div>
                    <div class="l-stat-label">Resolved</div>
                </div>
                <div class="l-stat">
                    <div class="l-stat-num">48<b>h</b></div>
                    <div class="l-stat-label">Avg Response</div>
                </div>
            </div>

            <div class="live-feed">
                <div class="lf-header">
                    <span class="lf-title">Live Case Feed</span>
                    <div class="lf-live"><div class="lf-dot"></div>LIVE</div>
                </div>
                <div class="lf-items">
                    @foreach ($liveFeed->take(3) as $item)
                        <div class="lf-item">
                            <div class="lf-item-dot {{ $item['color'] }}"></div>
                            <div class="lf-item-text">
                                <div class="lf-item-id">{{ $item['id'] }}</div>
                                <div class="lf-item-type">{{ $item['type'] }}</div>
                            </div>
                            <div class="lf-item-time">{{ $item['time'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="left-bottom">
            <p class="left-footer-text">
                &copy; {{ date('Y') }} NEMA eCRMS
            </p>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="right">
        <div class="login-box">

            <div class="mobile-back">
                <a href="{{ route('home') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to home
                </a>
            </div>

            <div class="mobile-brand">
                <span class="mobile-brand-name">NEMA <span>eCRMS</span></span>
            </div>

            <div class="login-header">
                <div class="login-eyebrow">Admin Portal</div>
                <h1 class="login-title">Welcome back,<br><em>Officer.</em></h1>
                <p class="login-sub">Sign in to your account.</p>
            </div>

            @if (session('status'))
                <div class="flash-banner">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="login-form" id="loginForm" novalidate>
                @csrf

                <div class="form-group">
                    <label class="form-label" for="emailInput">Email Address</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input
                            type="email"
                            name="email"
                            id="emailInput"
                            class="form-input @error('email') error @enderror"
                            value="{{ old('email') }}"
                            placeholder="name@nema.go.ug"
                            autocomplete="email"
                            required
                            autofocus
                        >
                        <div class="input-focus-line"></div>
                    </div>
                    <div class="error-msg @error('email') show @enderror" id="emailError">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        @error('email')
                            {{ $message }}
                        @else
                            Please enter a valid email address.
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pwdInput">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input
                            type="password"
                            name="password"
                            id="pwdInput"
                            class="form-input @error('password') error @enderror"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <div class="input-focus-line"></div>
                        <button type="button" class="pwd-toggle" id="pwdToggle" aria-label="Toggle password visibility">
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="error-msg @error('password') show @enderror" id="pwdError">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        @error('password')
                            {{ $message }}
                        @else
                            Password must be at least 8 characters.
                        @enderror
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">
                        Sign in to eCRMS
                        <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </span>
                    <div class="btn-spinner">
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                    </div>
                </button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">Security Notice</span>
                <div class="divider-line"></div>
            </div>

            <div class="security-notice">
                <div class="security-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="security-text">
                    <strong>Authorised Access Only</strong>
                    <p>Access is restricted to authorised NEMA personnel and registered environmental partners. All activities are monitored and logged.</p>
                </div>
            </div>

            <div class="session-badge">
                <div class="session-dot"></div>
                <span>256-bit SSL encryption &nbsp;&middot;&nbsp; Session auto-expires in 8 hours</span>
            </div>
        </div>

        <div class="right-footer">
            &copy; {{ date('Y') }} NEMA eCRMS &nbsp;&middot;&nbsp;
            <a href="{{ route('home') }}">Public Portal</a>
        </div>
    </div>
</div>

<script type="application/json" id="live-feed-data">@json($liveFeed->values())</script>

</body>
</html>

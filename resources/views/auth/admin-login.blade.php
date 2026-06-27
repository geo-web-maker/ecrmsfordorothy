<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Sign In — {{ config('app.name') }}</title>
    @include('partials.optimized-head')
    <style>
        body, input, button {
            font-family: 'Inter', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Input focus ring */
        .green-input:focus {
            outline: none;
            background: #F3F5EA !important;
            box-shadow: inset 0 2px 4px rgba(63,107,42,0.10), 0 0 0 4px rgba(94,139,61,0.18) !important;
        }

        /* Button hover */
        .login-btn:hover {
            background: #2d4f1f !important;
            box-shadow: 0 6px 24px rgba(63,107,42,0.40) !important;
            transform: translateY(-1px);
        }
        .login-btn:active {
            transform: translateY(0);
        }
        .login-btn { transition: background 0.2s, box-shadow 0.2s, transform 0.2s; }

        /* Floating decorative circles on left panel */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        /* Password toggle eye button */
        .pw-wrapper { position: relative; }
        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #5E8B3D;
        }

        /* Spinner on submit */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        /* Fade-in animation for right panel */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        /* Checkbox accent */
        input[type="checkbox"] { accent-color: #5E8B3D; }
    </style>
</head>
<body style="background: #F3F5EA;">
@include('partials.page-skeleton')

<div class="flex min-h-screen page-content">

    {{-- ===================== LEFT PANEL ===================== --}}
    <div class="hidden md:flex flex-col justify-between relative overflow-hidden"
         style="flex: 0 0 42%; background: #3F6B2A; padding: 48px 44px;">

        <!-- Decorative circles -->
        <div class="deco-circle" style="width:300px; height:300px; top:-100px; left:-100px;"></div>
        <div class="deco-circle" style="width:200px; height:200px; bottom:-60px; right:-60px;"></div>
        <div class="deco-circle" style="width:80px; height:80px; top:42%; left:60%;"></div>

        <!-- Top: Branding -->
        <div>
            <div class="flex items-center gap-2 mb-6">
                <div style="width:10px; height:10px; border-radius:50%; background:#DDE8C8;"></div>
                <span style="font-size:11px; font-weight:700; color:#DDE8C8; letter-spacing:2.5px; text-transform:uppercase;">
                    {{ config('app.name') }}
                </span>
            </div>

            <!-- Secure badge -->
            <div class="inline-flex items-center gap-2 mb-5"
                 style="background:rgba(221,232,200,0.15); border-radius:20px; padding:5px 12px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#DDE8C8" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span style="font-size:10px; font-weight:700; color:#DDE8C8; letter-spacing:0.5px;">Staff Portal</span>
            </div>

            <h1 style="font-size:28px; font-weight:800; color:white; line-height:1.35; margin:0 0 14px;">
                Protecting Uganda's environment, one report at a time.
            </h1>
            <p style="font-size:12px; color:rgba(221,232,200,0.75); line-height:1.7; margin:0;">
                Sign in to manage, investigate, and respond to environmental crime reports submitted across the country.
            </p>
        </div>

        <!-- Middle: Feature list -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#DDE8C8" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <p style="font-size:12px; font-weight:700; color:white; margin:0;">Anonymous reports secured</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.65); margin:0;">Reporter data handled confidentially</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#DDE8C8" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <p style="font-size:12px; font-weight:700; color:white; margin:0;">Real-time status updates</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.65); margin:0;">Live case progress tracking</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#DDE8C8" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div>
                    <p style="font-size:12px; font-weight:700; color:white; margin:0;">Investigation dashboard</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.65); margin:0;">Full case history and assignments</p>
                </div>
            </div>
        </div>

        <!-- Bottom: Footer -->
        <div>
            <hr style="border:none; border-top:1px solid rgba(221,232,200,0.15); margin-bottom:16px;">
            <p style="font-size:10px; color:rgba(221,232,200,0.4); margin:0;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>

    {{-- ===================== RIGHT PANEL ===================== --}}
    <div class="flex flex-col justify-center flex-1 px-6 py-12 md:px-16"
         style="background: #F3F5EA;">

        <div class="mx-auto w-full fade-up" style="max-width: 400px;">

            <!-- Back to Home Link -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-bold text-xs uppercase tracking-[1.2px] text-[#7B8F69] hover:text-[#3F6B2A] transition-colors" style="text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to home
                </a>
            </div>

            <!-- Mobile-only logo -->
            <div class="flex items-center gap-2 mb-8 md:hidden">
                <div style="width:8px; height:8px; border-radius:50%; background:#3F6B2A;"></div>
                <span style="font-size:11px; font-weight:700; color:#3F6B2A; letter-spacing:2px; text-transform:uppercase;">
                    {{ config('app.name') }}
                </span>
            </div>

            <!-- Heading -->
            <div class="mb-8">
                <div class="inline-flex items-center gap-1.5 mb-3"
                     style="font-size:10px; font-weight:700; color:#5E8B3D; letter-spacing:1.5px; text-transform:uppercase;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#5E8B3D" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Staff access only
                </div>
                <h2 style="font-size:30px; font-weight:800; color:#3F6B2A; margin:0 0 6px;">Welcome back</h2>
                <p style="font-size:13px; color:#7B8F69; margin:0;">Sign in to the NEMA eCRMS staff dashboard</p>
            </div>

            @include('partials.flash')

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.store') }}" id="admin-login-form" novalidate>
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label for="email"
                           class="flex items-center gap-1.5 mb-2"
                           style="font-size:10px; font-weight:700; color:#3F6B2A; letter-spacing:1.2px; text-transform:uppercase;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Email address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="green-input w-full"
                        style="padding:11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                    >
                    @error('email')
                        <div class="flex items-center gap-1.5 mt-2"
                             style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password"
                           class="flex items-center gap-1.5 mb-2"
                           style="font-size:10px; font-weight:700; color:#3F6B2A; letter-spacing:1.2px; text-transform:uppercase;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Password
                    </label>
                    <div class="pw-wrapper">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••••"
                            class="green-input w-full"
                            style="padding:11px 44px 11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePassword()" title="Show/hide password">
                            <svg id="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="flex items-center gap-1.5 mt-2"
                             style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2" style="font-size:12px; color:#7B8F69; cursor:pointer;">
                        <input type="checkbox" name="remember" id="remember_me">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           style="font-size:12px; font-weight:700; color:#5E8B3D; text-decoration:none;"
                           onmouseover="this.style.color='#3F6B2A';"
                           onmouseout="this.style.color='#5E8B3D';">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button
                    id="login-btn"
                    type="submit"
                    class="login-btn w-full flex items-center justify-center gap-2 font-bold text-white border-none cursor-pointer"
                    style="padding:13px; border-radius:12px; background:#3F6B2A; font-size:14px; box-shadow:0 4px 16px rgba(63,107,42,0.28);"
                >
                    <svg id="btn-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    <div class="spinner" id="btn-spinner"></div>
                    <span id="btn-text">Sign in to staff dashboard</span>
                </button>
            </form>

            <!-- Citizen portal link -->
            <p class="text-center" style="font-size:12px; color:#7B8F69; margin: 0;">
                Not staff?
                <a href="{{ route('login') }}"
                   style="font-weight:700; color:#5E8B3D; text-decoration:none;"
                   onmouseover="this.style.color='#3F6B2A';"
                   onmouseout="this.style.color='#5E8B3D';">
                    Citizen sign in →
                </a>
            </p>

        </div>
    </div>

</div>

<script>
    // Password show/hide toggle
    function togglePassword() {
        const pw = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            pw.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Button loading state on submit
    document.getElementById('admin-login-form').addEventListener('submit', function () {
        const btn = document.getElementById('login-btn');
        const icon = document.getElementById('btn-icon');
        const spinner = document.getElementById('btn-spinner');
        const text = document.getElementById('btn-text');
        icon.style.display = 'none';
        spinner.style.display = 'block';
        text.textContent = 'Signing in...';
        btn.disabled = true;
        btn.style.opacity = '0.85';
    });
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — {{ config('app.name') }}</title>
    @include('partials.optimized-head')
    <style>
        body, input, button {
            font-family: 'Inter', sans-serif;
        }

        html, body { height: 100%; margin: 0; padding: 0; }

        .green-input:focus {
            outline: none;
            background: #F3F5EA !important;
            box-shadow: inset 0 2px 4px rgba(63,107,42,0.10), 0 0 0 4px rgba(94,139,61,0.18) !important;
        }

        @keyframes attractRegister {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94,139,61,0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px 5px rgba(94,139,61,0.3); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94,139,61,0); }
        }

        .register-btn {
            animation: attractRegister 2.5s ease-in-out infinite;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        }

        .register-btn:hover {
            animation-play-state: paused;
            background: #2d4f1f !important;
            box-shadow: 0 6px 24px rgba(63,107,42,0.40) !important;
            transform: translateY(-1px) scale(1.05);
        }
        .register-btn:active { transform: translateY(0) scale(1); }

        .deco-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

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

        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        /* Password strength bar */
        .strength-bar { height: 4px; border-radius: 2px; transition: width 0.3s ease, background 0.3s ease; width: 0%; }

        input[type="checkbox"] { accent-color: #5E8B3D; }
    </style>
</head>
<body style="background: #F3F5EA;">
@include('partials.page-skeleton')
@include('partials.auth-nav')

<div class="flex min-h-screen page-content flex-col">

<div class="flex flex-1 min-h-0">

    {{-- ===================== LEFT PANEL ===================== --}}
    <div class="hidden md:flex flex-col justify-between relative overflow-hidden"
         style="flex: 0 0 42%; background: #3F6B2A; padding: 48px 44px;">

        <div class="deco-circle" style="width:300px; height:300px; top:-100px; left:-100px;"></div>
        <div class="deco-circle" style="width:200px; height:200px; bottom:-60px; right:-60px;"></div>
        <div class="deco-circle" style="width:80px; height:80px; top:40%; left:62%;"></div>

        <!-- Branding -->
        <div>
            @include('partials.brand-full-name', ['variant' => 'light', 'class' => 'mb-6'])

            <div class="inline-flex items-center gap-2 mb-5"
                 style="background:rgba(221,232,200,0.15); border-radius:20px; padding:5px 12px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#DDE8C8" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-size:10px; font-weight:700; color:#DDE8C8; letter-spacing:0.5px;">Citizen registration</span>
            </div>

            <h1 style="font-size:28px; font-weight:800; color:white; line-height:1.35; margin:0 0 14px;">
                Report environmental crimes and protect Uganda's environment.
            </h1>
            <p style="font-size:12px; color:rgba(221,232,200,0.75); line-height:1.7; margin:0;">
                Create your citizen account to submit reports, upload evidence, and track case progress securely.
            </p>
        </div>

        <!-- Steps -->
        <div class="flex flex-col gap-5">
            <p style="font-size:10px; font-weight:700; color:rgba(221,232,200,0.5); letter-spacing:1.5px; text-transform:uppercase; margin:0;">
                Getting started
            </p>
            <div class="flex items-start gap-3">
                <div style="width:26px; height:26px; border-radius:50%; background:#DDE8C8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:11px; font-weight:800; color:#3F6B2A;">1</span>
                </div>
                <div style="padding-top:3px;">
                    <p style="font-size:12px; font-weight:700; color:white; margin:0;">Create your account</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.6); margin:0;">Fill in your name, email and password</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div style="width:26px; height:26px; border-radius:50%; background:rgba(221,232,200,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:11px; font-weight:800; color:#DDE8C8;">2</span>
                </div>
                <div style="padding-top:3px;">
                    <p style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.7); margin:0;">Verify your email</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.5); margin:0;">Check your inbox for a confirmation link</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div style="width:26px; height:26px; border-radius:50%; background:rgba(221,232,200,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:11px; font-weight:800; color:#DDE8C8;">3</span>
                </div>
                <div style="padding-top:3px;">
                    <p style="font-size:12px; font-weight:700; color:white; margin:0;">Access your dashboard</p>
                    <p style="font-size:11px; color:rgba(221,232,200,0.5); margin:0;">Submit and track environmental crime reports</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div>
            <hr style="border:none; border-top:1px solid rgba(221,232,200,0.15); margin-bottom:16px;">
            <p style="font-size:10px; color:rgba(221,232,200,0.4); margin:0;">
                © {{ date('Y') }} NEMA — Environmental Crime Reporting & Monitoring System.
            </p>
        </div>
    </div>

    {{-- ===================== RIGHT PANEL ===================== --}}
    <div class="flex flex-col justify-center flex-1 px-6 py-12 md:px-14"
         style="background: #F3F5EA; overflow-y: auto;">

        <div class="mx-auto w-full fade-up" style="max-width: 420px;">

            <!-- Back to Home Link -->
            <div class="mb-7">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-bold text-xs uppercase tracking-[1.2px] text-[#7B8F69] hover:text-[#3F6B2A] transition-colors" style="text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to home
                </a>
            </div>

            <!-- Heading -->
            <div class="mb-7">
                <h2 style="font-size:28px; font-weight:800; color:#3F6B2A; margin:0 0 5px;">Create your account</h2>
                <p style="font-size:13px; color:#7B8F69; margin:0;">Create your NEMA citizen account for the Environmental Crime Reporting & Monitoring System</p>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name"
                           class="flex items-center gap-1.5 mb-2"
                           style="font-size:10px; font-weight:700; color:#3F6B2A; letter-spacing:1.2px; text-transform:uppercase;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Full name
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="e.g. Jane Nakato"
                        class="green-input w-full"
                        style="padding:11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                    >
                    @error('name')
                        <div class="flex items-center gap-1.5 mt-1.5" style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
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
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="green-input w-full"
                        style="padding:11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                    >
                    @error('email')
                        <div class="flex items-center gap-1.5 mt-1.5" style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-2">
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
                            autocomplete="new-password"
                            placeholder="••••••••••"
                            class="green-input w-full"
                            oninput="checkStrength(this.value)"
                            style="padding:11px 44px 11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePassword('password','eye-icon-1')" title="Show/hide password">
                            <svg id="eye-icon-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div style="background:#DDE8C8; border-radius:2px; height:4px; margin-top:8px;">
                        <div class="strength-bar" id="strength-bar"></div>
                    </div>
                    <p id="strength-label" style="font-size:10px; color:#7B8F69; margin-top:4px; font-weight:600;"></p>
                    @error('password')
                        <div class="flex items-center gap-1.5 mt-1.5" style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation"
                           class="flex items-center gap-1.5 mb-2"
                           style="font-size:10px; font-weight:700; color:#3F6B2A; letter-spacing:1.2px; text-transform:uppercase;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Confirm password
                    </label>
                    <div class="pw-wrapper">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••••"
                            class="green-input w-full"
                            oninput="checkMatch()"
                            style="padding:11px 44px 11px 16px; border-radius:12px; border:2px solid #5E8B3D; background:#DDE8C8; font-size:14px; color:#3F6B2A; font-weight:600; transition:all 0.2s; box-shadow:inset 0 2px 4px rgba(63,107,42,0.06);"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePassword('password_confirmation','eye-icon-2')" title="Show/hide password">
                            <svg id="eye-icon-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <p id="match-label" style="font-size:10px; margin-top:4px; font-weight:600;"></p>
                    @error('password_confirmation')
                        <div class="flex items-center gap-1.5 mt-1.5" style="font-size:11px; font-weight:600; color:#c0392b;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit -->
                <button
                    id="register-btn"
                    type="submit"
                    class="register-btn w-full flex items-center justify-center gap-2 font-bold text-white border-none cursor-pointer"
                    style="padding:13px; border-radius:12px; background:#3F6B2A; font-size:14px; box-shadow:0 4px 16px rgba(63,107,42,0.28);"
                >
                    <svg id="btn-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    <div class="spinner" id="btn-spinner"></div>
                    <span id="btn-text">Create account</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-5">
                <div style="flex:1; height:1px; background:#DDE8C8;"></div>
                <span style="font-size:10px; font-weight:700; color:#7B8F69;">or</span>
                <div style="flex:1; height:1px; background:#DDE8C8;"></div>
            </div>

            <!-- Already registered -->
            <p class="text-center" style="font-size:12px; color:#7B8F69;">
                Already have an account?
                <a href="{{ route('login') }}"
                   style="font-weight:700; color:#5E8B3D; text-decoration:none;"
                   onmouseover="this.style.color='#3F6B2A';"
                   onmouseout="this.style.color='#5E8B3D';">
                    Sign in instead →
                </a>
            </p>

        </div>
    </div>

</div>
</div>

<script>
    // Password show/hide
    function togglePassword(fieldId, iconId) {
        const pw = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            pw.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Password strength checker
    function checkStrength(val) {
        const bar = document.getElementById('strength-bar');
        const label = document.getElementById('strength-label');
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { w: '0%',   bg: 'transparent', text: '' },
            { w: '25%',  bg: '#c0392b',     text: 'Weak' },
            { w: '50%',  bg: '#e67e22',     text: 'Fair' },
            { w: '75%',  bg: '#5E8B3D',     text: 'Good' },
            { w: '100%', bg: '#3F6B2A',     text: 'Strong' },
        ];

        const level = val.length === 0 ? levels[0] : levels[score] || levels[1];
        bar.style.width = level.w;
        bar.style.background = level.bg;
        label.textContent = level.text;
        label.style.color = level.bg;

        checkMatch();
    }

    // Password match checker
    function checkMatch() {
        const pw = document.getElementById('password').value;
        const conf = document.getElementById('password_confirmation').value;
        const label = document.getElementById('match-label');
        if (conf.length === 0) { label.textContent = ''; return; }
        if (pw === conf) {
            label.textContent = '✓ Passwords match';
            label.style.color = '#3F6B2A';
        } else {
            label.textContent = '✗ Passwords do not match';
            label.style.color = '#c0392b';
        }
    }

    // Button loading on submit
    document.getElementById('register-form').addEventListener('submit', function () {
        const btn = document.getElementById('register-btn');
        const icon = document.getElementById('btn-icon');
        const spinner = document.getElementById('btn-spinner');
        const text = document.getElementById('btn-text');
        icon.style.display = 'none';
        spinner.style.display = 'block';
        text.textContent = 'Creating account...';
        btn.disabled = true;
        btn.style.opacity = '0.85';
    });
</script>

</body>
</html>
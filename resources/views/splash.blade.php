<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background-color:#012d1d;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name', 'ECRMS') }} | Secure Loading</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        html, body { background-color: #012d1d; margin: 0; }
        body.splash-screen { opacity: 0; }
        body.splash-screen.is-ready { opacity: 1; transition: opacity 0.2s ease; }
    </style>
    @vite(['resources/css/app.css', 'resources/css/splash-page.css'])
</head>
<body class="splash-screen relative overflow-x-hidden bg-portal-ink font-sans text-white" id="splash-screen">
    @php
        $splashBg = file_exists(public_path('images/splash-bg.png'))
            ? asset('images/splash-bg.png')
            : 'https://lh3.googleusercontent.com/aida-public/AB6AXuBAt9QFNpl-F9X5GgA9tjoDPghMVi7wWL3g4-tzBGJ_z62ntW1mo2pWqAVW_YLS6b_quo-_4yTIkN-7RwktCKD3KqwjA-90eIkyloikkJYYbG-QI-a9HwHdpp5UYNVe2JUKsrLsDzAMFffMi2-meo8HzKwx2qPc6RCt6KZejlNsAo9rk574GyanSzLTWZP5RqQRobXx_kWI2FqL-kqPdRMF10_oHM2AUQhcd0XEqceHfyDsbRuug8_wA5SLh7ho_CgN4_xDP73p2huL';
    @endphp

    <div
        class="absolute inset-0 opacity-20 bg-cover bg-center mix-blend-overlay pointer-events-none"
        style="background-image: url('{{ $splashBg }}');"
        role="presentation"
        aria-hidden="true"
    ></div>
    <div class="absolute inset-0 bg-gradient-to-b from-portal-ink/80 via-portal-ink to-portal-ink pointer-events-none" aria-hidden="true"></div>

    <div class="splash-layout relative z-10">
        <header class="splash-brand flex items-center justify-center gap-3">
            <div class="p-2 rounded-lg bg-[#a1f4c8]/10 border border-[#a1f4c8]/20 shrink-0">
                <svg class="splash-icon w-8 h-8 sm:w-9 sm:h-9 text-[#a1f4c8]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm0 2.18l6 2.25v4.66c0 4.12-2.73 7.95-6 8.97-3.27-1.02-6-4.85-6-8.97V6.43l6-2.25z"/>
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#86af99] tracking-tight leading-tight text-center">
                NEMA <span class="text-[#a1f4c8]">eCRMS</span>
            </h1>
        </header>

        <div class="splash-loader-wrap flex items-center justify-center" aria-hidden="true">
            <div class="relative flex items-center justify-center">
                <div class="absolute w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-[#a1f4c8] opacity-20 splash-pulse-ring"></div>
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-t-[#a1f4c8] border-r-transparent border-b-transparent border-l-transparent splash-loader"></div>
                <div class="absolute flex flex-col items-center justify-center">
                    <svg class="splash-icon w-7 h-7 sm:w-8 sm:h-8 text-[#a1f4c8] opacity-80" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                    <p class="text-[10px] sm:text-xs font-semibold text-[#a1f4c8]/60 mt-1.5 sm:mt-2 uppercase tracking-[0.2em] sm:tracking-widest">Securing</p>
                </div>
            </div>
        </div>

        <footer class="splash-footer text-center w-full max-w-sm sm:max-w-md mx-auto">
            <p class="text-sm sm:text-base text-white font-semibold leading-snug text-balance px-1">
                Protecting Uganda's Natural Heritage
            </p>
            <div class="mt-3 sm:mt-4 flex flex-col items-center gap-2 sm:gap-2.5">
                <p class="splash-encryption text-[11px] sm:text-xs font-semibold text-[#a1f4c8]/70 leading-snug text-balance">
                    <svg class="splash-icon splash-encryption-icon w-4 h-4 text-[#a1f4c8] inline-block align-[-0.15em] mr-1" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    100% Anonymous &amp; End-to-End Encrypted
                </p>
                <p class="text-sm text-[#a1f4c8]/90 splash-hint-pulse {{ ($fromLogout ?? false) ? '' : 'animate-pulse' }} font-medium leading-snug">
                    @if ($fromLogout ?? false)
                        Logging you out securely&hellip;
                    @else
                        Tap anywhere to continue
                    @endif
                </p>
                <p class="text-[9px] sm:text-[10px] text-[#86af99]/50 uppercase tracking-[0.12em] sm:tracking-[0.2em] font-medium leading-relaxed">
                    Official Government Portal
                </p>
            </div>
        </footer>
    </div>

    <script>
        (() => {
            const continueUrl = @json($continueUrl);
            const delayMs = @json($autoDelayMs ?? 6500);
            const fromLogout = @json($fromLogout ?? false);
            let dismissed = false;

            function revealSplash() {
                document.body.classList.add('is-ready');
            }

            function continueToApp() {
                if (dismissed) return;
                dismissed = true;
                window.location.href = continueUrl;
            }

            if (document.readyState === 'complete') {
                revealSplash();
            } else {
                window.addEventListener('load', revealSplash, { once: true });
            }

            document.getElementById('splash-screen').addEventListener('click', continueToApp);
            if (!fromLogout) {
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        continueToApp();
                    }
                });
            }

            window.setTimeout(continueToApp, delayMs);
        })();
    </script>
</body>
</html>

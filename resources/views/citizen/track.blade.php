<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Report — {{ config('app.name') }}</title>
    @include('partials.optimized-head')
    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Result section hidden by default, revealed with animation */
        #report-results {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        #report-results.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Staggered entrance for result content */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #report-results .animate-item {
            opacity: 0;
        }

        #report-results.visible .animate-item {
            animation: fadeInUp 0.55s ease forwards;
        }

        #report-results.visible .animate-delay-1 { animation-delay: 0.15s; }
        #report-results.visible .animate-delay-2 { animation-delay: 0.25s; }
        #report-results.visible .animate-delay-3 { animation-delay: 0.35s; }
        #report-results.visible .animate-delay-4 { animation-delay: 0.45s; }
        #report-results.visible .animate-delay-5 { animation-delay: 0.55s; }
        #report-results.visible .animate-delay-6 { animation-delay: 0.65s; }

        /* Page section entrance on first load */
        .track-enter {
            opacity: 0;
            transform: translateY(20px);
        }

        html.is-ready .track-enter {
            animation: fadeInUp 0.6s ease forwards;
        }

        html.is-ready .track-enter--delay-1 { animation-delay: 0.1s; }
        html.is-ready .track-enter--delay-2 { animation-delay: 0.2s; }

        #scroll-hint {
            opacity: 0;
        }

        #scroll-hint.visible {
            animation: fadeInUp 0.5s ease 0.8s forwards;
        }

        @media (prefers-reduced-motion: reduce) {
            #report-results,
            #report-results.visible,
            #report-results .animate-item,
            #report-results.visible .animate-item,
            .track-enter,
            #scroll-hint,
            #scroll-hint.visible {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }

        /* Animated scroll-down arrow bounce */
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(6px);
            }
        }

        .bounce {
            animation: bounce 1.4s ease-in-out infinite;
        }

        /* Button loading spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        /* Status badge pulse for "Under Review" */
        @keyframes pulse-green {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(94, 139, 61, 0.4);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(94, 139, 61, 0);
            }
        }

        .status-pulse {
            animation: pulse-green 2s ease-in-out infinite;
        }

        /* Card hover lift */
        .result-card {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(94, 139, 61, 0.18) !important;
        }
    </style>
</head>

<body class="antialiased" style="background: #F3F5EA;">
@include('partials.page-skeleton')
<div class="page-content">
    <div class="px-6 pt-6 md:px-12">
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-1 text-sm font-semibold transition-all hover:gap-2"
            style="color: #3F6B2A;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Back to Home
        </a>
    </div>

    <!-- Main Track Section -->
    <div class="min-h-screen px-6 py-12 md:px-12" style="background: #F3F5EA;">
        <div class="mx-auto max-w-7xl">

            <!-- Header -->
            <div class="mb-10 text-center md:text-left track-enter">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3" style="color: #3F6B2A;">Track Your
                    Report</h1>
                <p class="text-lg max-w-2xl" style="color: #7B8F69;">Enter the tracking code you received when you
                    submitted an anonymous environmental crime report. Monitor the progress and status of your case.</p>
            </div>

            @include('partials.flash')

            <!-- Two-Column Layout -->
            <div class="flex flex-col md:flex-row gap-8 items-stretch">

                <!-- Left Column: Form (40%) -->
                <div class="flex-1 md:flex-[0_0_40%] flex flex-col track-enter track-enter--delay-1">
                    <form id="track-form" method="POST" action="{{ route('report.tracking.result') }}"
                        class="flex flex-col flex-1" novalidate>
                        @csrf

                        <!-- Form Card -->
                        <div class="rounded-2xl p-8 flex flex-col flex-1 justify-between"
                            style="background: rgba(255,255,255,0.97);
                                    box-shadow: 0 4px 24px rgba(63,107,42,0.10);
                                    border-left: 4px solid #5E8B3D;
                                    border-top: 1px solid rgba(94,139,61,0.10);
                                    border-right: 1px solid rgba(94,139,61,0.10);
                                    border-bottom: 1px solid rgba(94,139,61,0.10);">

                            <div class="flex flex-col gap-6">

                                <!-- Progress Steps -->
                                <div>
                                    <div class="flex gap-2 mb-2">
                                        <div class="h-1 flex-1 rounded-full" style="background: #5E8B3D;"></div>
                                        <div class="h-1 flex-1 rounded-full" style="background: #5E8B3D;"></div>
                                        <div class="h-1 flex-1 rounded-full" id="step3-bar"
                                            style="background: #DDE8C8; transition: background 0.5s ease;"></div>
                                    </div>
                                    <p class="text-xs font-semibold" style="color: #7B8F69;">Step 2 of 3 — Enter your
                                        code</p>
                                </div>

                                <!-- Tracking Code Input -->
                                <div>
                                    <label for="tracking_code"
                                        class="flex items-center gap-1.5 text-xs font-bold mb-3 uppercase tracking-widest"
                                        style="color: #3F6B2A;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                        </svg>
                                        Tracking Code
                                    </label>
                                    <input type="text" name="tracking_code" id="tracking_code"
                                        value="{{ old('tracking_code', request('tracking_code')) }}"
                                        placeholder="e.g. A1B2C3D4E5" required
                                        class="w-full px-5 py-3 text-base font-mono uppercase rounded-xl font-semibold transition-all outline-none"
                                        style="background: #DDE8C8; border: 2px solid #5E8B3D; color: #3F6B2A; box-shadow: inset 0 2px 4px rgba(63,107,42,0.06);"
                                        onfocus="this.style.boxShadow='inset 0 2px 4px rgba(63,107,42,0.10), 0 0 0 4px rgba(94,139,61,0.18)'; this.style.background='#F3F5EA';"
                                        onblur="this.style.boxShadow='inset 0 2px 4px rgba(63,107,42,0.06)'; this.style.background='#DDE8C8';">
                                    <p class="text-xs mt-2" style="color: #7B8F69;">Format: 10-character alphanumeric
                                        code</p>
                                    @error('tracking_code')
                                        <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold"
                                            style="color: #c0392b;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Divider -->
                                <hr style="border: none; border-top: 1px dashed #DDE8C8;">

                                <!-- Trust Bullets -->
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center"
                                            style="background: #DDE8C8;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="#3F6B2A" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: #3F6B2A;">Fully anonymous
                                            </p>
                                            <p class="text-xs" style="color: #7B8F69;">No personal data is stored or
                                                linked to your report.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center"
                                            style="background: #DDE8C8;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="#3F6B2A" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: #3F6B2A;">Real-time status
                                                updates</p>
                                            <p class="text-xs" style="color: #7B8F69;">See live progress as
                                                investigators review your case.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center"
                                            style="background: #DDE8C8;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="#3F6B2A" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <polyline points="14 2 14 8 20 8" />
                                                <line x1="16" y1="13" x2="8" y2="13" />
                                                <line x1="16" y1="17" x2="8" y2="17" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold" style="color: #3F6B2A;">Full case history
                                            </p>
                                            <p class="text-xs" style="color: #7B8F69;">Access investigator notes and
                                                all case updates in one place.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Bottom: Button + Footer -->
                            <div class="mt-8">
                                <button id="track-btn" type="submit"
                                    class="w-full font-bold text-white text-base rounded-xl px-6 py-3 flex items-center justify-center gap-2 cursor-pointer border-none"
                                    style="background: #3F6B2A; box-shadow: 0 4px 16px rgba(63,107,42,0.25); transition: background 0.2s, box-shadow 0.2s, transform 0.2s;"
                                    onmouseover="this.style.background='#2d4f1f'; this.style.boxShadow='0 6px 20px rgba(63,107,42,0.35)'; this.style.transform='translateY(-1px)';"
                                    onmouseout="this.style.background='#3F6B2A'; this.style.boxShadow='0 4px 16px rgba(63,107,42,0.25)'; this.style.transform='translateY(0)';">
                                    <svg id="btn-icon" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                    <div class="spinner" id="btn-spinner"></div>
                                    <span id="btn-text">Track Report</span>
                                </button>

                                <p class="text-center text-xs mt-5" style="color: #7B8F69;">
                                    Don't have a tracking code?
                                    <a href="{{ route('report.anonymous') }}" class="font-semibold transition-colors"
                                        style="color: #5E8B3D;" onmouseover="this.style.color='#3F6B2A';"
                                        onmouseout="this.style.color='#5E8B3D';">
                                        Submit a new report
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Image (60%) -->
                <div class="flex-1 md:flex-[0_0_60%] relative h-[280px] md:h-auto md:min-h-[400px] track-enter track-enter--delay-2">
                    <x-lazy-image
                        :src="asset('images/tracking-image.png')"
                        alt="Environmental Protection"
                        class="w-full h-full object-cover rounded-[20px]"
                        height="400px"
                        priority
                    />
                    <div
                        style="position: absolute; inset: 0; border-radius: 20px; background: linear-gradient(to top, rgba(39,80,10,0.55) 0%, transparent 55%);">
                    </div>
                    <div
                        style="position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.92); border-radius: 10px; padding: 8px 14px; display: flex; align-items: center; gap: 6px; backdrop-filter: blur(4px);">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#3F6B2A"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span style="font-size: 11px; font-weight: 700; color: #3F6B2A;">Uganda Environmental
                            Watch</span>
                    </div>
                    <div
                        style="position: absolute; top: 16px; right: 16px; background: #5E8B3D; border-radius: 20px; padding: 5px 14px; display: flex; align-items: center; gap: 5px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white"
                            stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span style="font-size: 11px; font-weight: 700; color: white;">Protected Reporting</span>
                    </div>
                </div>

            </div>

            {{-- Scroll hint arrow — only shown when report exists --}}
            @isset($report)
                <div id="scroll-hint" class="flex justify-center mt-10">
                    <button onclick="document.getElementById('report-results').scrollIntoView({behavior:'smooth'})"
                        class="bounce flex flex-col items-center gap-1 border-none bg-transparent cursor-pointer"
                        style="color: #7B8F69;">
                        <span class="text-xs font-semibold">View your report status</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                </div>
            @endisset

            <!-- Report Results -->
            @isset($report)
                <div id="report-results" class="mt-10">
                    <div class="result-card rounded-3xl p-8 md:p-10"
                        style="background: rgba(255,255,255,0.95);
                                border: 2px solid #5E8B3D;
                                box-shadow: 0 8px 32px rgba(94,139,61,0.12);">

                        <!-- Result Header -->
                        <div class="animate-item animate-delay-1 flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6"
                            style="border-bottom: 1px solid rgba(94,139,61,0.15);">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #7B8F69;">Case
                                    Found</p>
                                <h2 class="text-3xl font-bold" style="color: #3F6B2A;">Report #{{ $report->id }}</h2>
                            </div>
                            <!-- Live status pill -->
                            <div class="status-pulse inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold"
                                style="background: #DDE8C8; color: #3F6B2A; border: 1.5px solid #5E8B3D;">
                                <span
                                    style="width:8px; height:8px; border-radius:50%; background:#5E8B3D; display:inline-block;"></span>
                                {{ $report->status }}
                            </div>
                        </div>

                        <dl>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="animate-item animate-delay-2 rounded-xl p-5" style="background: #F3F5EA;">
                                    <dt class="text-xs font-bold mb-1 uppercase tracking-widest" style="color: #7B8F69;">
                                        Tracking Code</dt>
                                    <dd class="text-lg font-mono font-semibold" style="color: #3F6B2A;">
                                        {{ $report->tracking_code }}</dd>
                                </div>
                                <div class="animate-item animate-delay-3 rounded-xl p-5" style="background: #F3F5EA;">
                                    <dt class="text-xs font-bold mb-1 uppercase tracking-widest" style="color: #7B8F69;">
                                        Category</dt>
                                    <dd class="text-lg font-semibold" style="color: #3F6B2A;">
                                        {{ $report->crimeCategory->name ?? '—' }}</dd>
                                </div>
                                <div class="animate-item animate-delay-4 rounded-xl p-5" style="background: #F3F5EA;">
                                    <dt class="text-xs font-bold mb-1 uppercase tracking-widest" style="color: #7B8F69;">
                                        Priority</dt>
                                    <dd class="text-lg font-semibold" style="color: #3F6B2A;">{{ $report->priority }}
                                    </dd>
                                </div>
                                <div class="animate-item animate-delay-5 rounded-xl p-5" style="background: #F3F5EA;">
                                    <dt class="text-xs font-bold mb-1 uppercase tracking-widest" style="color: #7B8F69;">
                                        Submitted</dt>
                                    <dd class="text-lg font-semibold" style="color: #3F6B2A;">
                                        {{ $report->created_at->format('M d, Y \a\t H:i') }}</dd>
                                </div>
                            </div>

                            <div class="animate-item animate-delay-6 mt-6 rounded-xl p-5" style="background: #F3F5EA;">
                                <dt class="text-xs font-bold mb-2 uppercase tracking-widest" style="color: #7B8F69;">
                                    Description</dt>
                                <dd class="text-base leading-relaxed" style="color: #555;">{{ $report->description }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endisset

        </div>
    </div>

    <script>
        // 1. Button loading state on submit
        const form = document.getElementById('track-form');
        const btn = document.getElementById('track-btn');
        const btnIcon = document.getElementById('btn-icon');
        const btnSpinner = document.getElementById('btn-spinner');
        const btnText = document.getElementById('btn-text');

        if (form) {
            form.addEventListener('submit', function() {
                btnIcon.style.display = 'none';
                btnSpinner.style.display = 'block';
                btnText.textContent = 'Searching...';
                btn.disabled = true;
                btn.style.opacity = '0.85';
                // Complete the 3rd progress bar step
                const step3 = document.getElementById('step3-bar');
                if (step3) step3.style.background = '#5E8B3D';
            });
        }

        // 2. Smooth reveal of results section after page is ready
        const revealResults = () => {
            const results = document.getElementById('report-results');
            const scrollHint = document.getElementById('scroll-hint');
            if (!results || results.dataset.revealed) return;
            results.dataset.revealed = '1';

            setTimeout(() => {
                results.classList.add('visible');
                if (scrollHint) scrollHint.classList.add('visible');
                setTimeout(() => {
                    results.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            }, 350);
        };

        if (document.getElementById('report-results')) {
            const tryReveal = () => {
                if (document.documentElement.classList.contains('is-ready')) {
                    revealResults();
                    return true;
                }
                return false;
            };

            if (!tryReveal()) {
                const observer = new MutationObserver(() => {
                    if (tryReveal()) observer.disconnect();
                });
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        }
    </script>

    @isset($report)
        <button id="back-to-top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
            style="
        position: fixed;
        bottom: 32px;
        right: 32px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #3F6B2A;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(63,107,42,0.35);
        transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        animation: bounce 1.4s ease-in-out infinite;
        z-index: 999;
    "
            onmouseover="this.style.background='#2d4f1f'; this.style.boxShadow='0 6px 24px rgba(63,107,42,0.45)'; this.style.transform='translateY(-2px)';"
            onmouseout="this.style.background='#3F6B2A'; this.style.boxShadow='0 4px 16px rgba(63,107,42,0.35)'; this.style.transform='translateY(0)';"
            title="Back to top">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15" />
            </svg>
        </button>
    @endisset
</div>
</body>

</html>

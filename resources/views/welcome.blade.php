<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ECRMS') }} — Environmental Crime Reporting & Monitoring System</title>
    <meta name="description" content="Report environmental crimes safely and anonymously. NEMA eCRMS helps citizens protect Uganda's environment through encrypted, secure crime reporting.">
    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
        @vite(['resources/css/welcome-page.css'])
    @endpush
    @include('partials.optimized-head')
    <link rel="preload" as="image" href="{{ asset('images/Hero-section.jpg') }}" fetchpriority="high">
</head>
<body class="welcome-page antialiased font-sans text-portal-on-surface overflow-x-hidden">
@include('partials.page-skeleton')
@include('partials.public-nav')

<div class="page-content">

<main>
    <!-- Hero -->
    <section class="relative min-h-[600px] lg:h-[716px] flex items-center overflow-hidden">
        <div
            class="absolute inset-0 z-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/Hero-section.jpg') }}');"
            role="img"
            aria-label="Lush green forest canopy representing Uganda's protected environment"
        ></div>
        <div class="absolute inset-0 z-10 welcome-hero-gradient"></div>
        <div class="relative z-20 w-full px-4 sm:px-8 lg:px-portal-margin-desktop max-w-portal-container mx-auto text-center text-white pt-8 pb-16">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full mb-8 border border-white/20">
                <span class="material-symbols-outlined filled text-sm text-white">verified_user</span>
                <span class="text-xs font-semibold uppercase tracking-wider">Fully Encrypted System</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-portal-xl font-extrabold mb-6 max-w-4xl mx-auto flex flex-col items-center gap-2 sm:gap-3 md:gap-4">
                <span>Safe &amp; Fully</span>
                <span>Encrypted Reporting</span>
            </h1>
            <p class="text-lg sm:text-portal-md font-medium text-white/90 mb-4">Report Environmental Crimes Safely &amp; Anonymously</p>
            <p class="text-base sm:text-portal-body-lg text-white/80 max-w-2xl mx-auto mb-10">
                Submit illegal logging, wetland encroachment, pollution, and other environmental violations. Track your case from report to resolution.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('report.anonymous') }}" class="welcome-cta w-full sm:w-auto bg-white text-portal-ink px-10 py-4 rounded-xl text-sm font-bold shadow-xl hover:bg-portal-surface transition-all no-underline">
                    Report a Crime Now
                </a>
                <a href="{{ route('report.track') }}" class="welcome-cta w-full sm:w-auto bg-transparent border-2 border-white/40 text-white px-10 py-4 rounded-xl text-sm font-bold hover:border-white transition-all backdrop-blur-sm no-underline">
                    Track a Case
                </a>
            </div>
        </div>
    </section>

    <!-- Flash Messages -->
    <div class="max-w-portal-container mx-auto px-4 sm:px-8 lg:px-portal-margin-desktop -mt-6 relative z-30">
        @include('partials.flash')
    </div>

    <!-- System Features -->
    <section class="py-16 sm:py-24 bg-portal-surface px-4 sm:px-8 lg:px-portal-margin-desktop">
        <div class="max-w-portal-container mx-auto">
            <div class="text-center mb-12 sm:mb-16 welcome-reveal">
                <h2 class="text-2xl sm:text-portal-lg text-portal-ink font-bold mb-4">System Features</h2>
                <p class="text-base sm:text-portal-body-lg text-portal-on-surface-variant max-w-2xl mx-auto">
                    Designed with absolute security and a simple workflow to empower citizens and fast-track environmental protection.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="welcome-bento-card welcome-reveal bg-white p-8 rounded-xl border border-portal-outline-variant flex flex-col items-start h-full">
                    <div class="w-14 h-14 bg-portal-leaf-tint rounded-lg flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-portal-secondary text-portal-md">inventory_2</span>
                    </div>
                    <h3 class="text-portal-md text-portal-ink font-semibold mb-3">Submit with Evidence</h3>
                    <p class="text-sm sm:text-base text-portal-on-surface-variant leading-relaxed">Easily upload photos, coordinates, videos, and full descriptions of the violation.</p>
                </div>
                <div class="welcome-bento-card welcome-reveal bg-white p-8 rounded-xl border border-portal-outline-variant flex flex-col items-start h-full">
                    <div class="w-14 h-14 bg-portal-leaf-tint rounded-lg flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-portal-secondary text-portal-md">lock</span>
                    </div>
                    <h3 class="text-portal-md text-portal-ink font-semibold mb-3">Anonymous Tracking</h3>
                    <p class="text-sm sm:text-base text-portal-on-surface-variant leading-relaxed">Never worry about safety. Get a fully secure, hash-generated tracking code to monitor progress.</p>
                </div>
                <div class="welcome-bento-card welcome-reveal bg-white p-8 rounded-xl border border-portal-outline-variant flex flex-col items-start h-full">
                    <div class="w-14 h-14 bg-portal-leaf-tint rounded-lg flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-portal-secondary text-portal-md">verified</span>
                    </div>
                    <h3 class="text-portal-md text-portal-ink font-semibold mb-3">NEMA Verified</h3>
                    <p class="text-sm sm:text-base text-portal-on-surface-variant leading-relaxed">Reports are forwarded instantly to regional enforcement and environment field teams.</p>
                </div>
                <div class="welcome-bento-card welcome-reveal bg-white p-8 rounded-xl border border-portal-outline-variant flex flex-col items-start h-full">
                    <div class="w-14 h-14 bg-portal-leaf-tint rounded-lg flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-portal-secondary text-portal-md">notifications</span>
                    </div>
                    <h3 class="text-portal-md text-portal-ink font-semibold mb-3">Real-time Updates</h3>
                    <p class="text-sm sm:text-base text-portal-on-surface-variant leading-relaxed">Receive notifications and review progress updates until the incident has been resolved.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Report & Live Feed -->
    <section class="py-16 sm:py-24 bg-white px-4 sm:px-8 lg:px-portal-margin-desktop overflow-hidden">
        <div class="max-w-portal-container mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            <div class="lg:col-span-6 space-y-8 sm:space-y-12">
                <div class="welcome-reveal">
                    <h2 class="text-2xl sm:text-portal-lg text-portal-ink font-bold mb-6">Why Report Through NEMA eCRMS?</h2>
                    <p class="text-base sm:text-portal-body-lg text-portal-on-surface-variant">
                        Join citizens protecting Uganda's environment. Every report helps stop illegal logging, wetland destruction, and pollution.
                    </p>
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div class="welcome-reveal flex items-start gap-4 sm:gap-6 p-5 sm:p-6 rounded-xl border border-portal-outline-variant bg-portal-surface-bright hover:border-portal-secondary transition-colors">
                        <div class="mt-1 w-8 h-8 bg-portal-secondary-container text-portal-on-secondary-container rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined filled text-base">check</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-portal-ink mb-1">Strict Identity Protection</h4>
                            <p class="text-sm text-portal-on-surface-variant">We use encryption protocols to scrub metadata from your uploads and files.</p>
                        </div>
                    </div>
                    <div class="welcome-reveal flex items-start gap-4 sm:gap-6 p-5 sm:p-6 rounded-xl border border-portal-outline-variant bg-portal-surface-bright hover:border-portal-secondary transition-colors">
                        <div class="mt-1 w-8 h-8 bg-portal-secondary-container text-portal-on-secondary-container rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined filled text-base">check</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-portal-ink mb-1">Fast Response Routing</h4>
                            <p class="text-sm text-portal-on-surface-variant">Automatically alerts the closest local authority or NEMA forest ranger unit.</p>
                        </div>
                    </div>
                    <div class="welcome-reveal flex items-start gap-4 sm:gap-6 p-5 sm:p-6 rounded-xl border border-portal-outline-variant bg-portal-surface-bright hover:border-portal-secondary transition-colors">
                        <div class="mt-1 w-8 h-8 bg-portal-secondary-container text-portal-on-secondary-container rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined filled text-base">check</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-portal-ink mb-1">Transparent Case Progress</h4>
                            <p class="text-sm text-portal-on-surface-variant">View verification states, assigned officer notes, and field action dates.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-6">
                <div class="welcome-live-feed bg-portal-container p-6 sm:p-8 rounded-3xl shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl"></div>
                    <div class="flex justify-between items-start mb-8 sm:mb-10 gap-4">
                        <div>
                            <h3 class="text-portal-md font-semibold mb-1">Active Case Live-Feed</h3>
                            <p class="text-white/60 text-xs uppercase tracking-widest">Secure Telemetry Tracking View</p>
                        </div>
                        <div class="flex items-center gap-2 bg-portal-leaf-tint/20 text-portal-leaf-tint px-3 py-1 rounded-full border border-portal-leaf-tint/30 shrink-0">
                            <span class="w-2 h-2 rounded-full bg-portal-leaf-tint animate-pulse"></span>
                            <span class="text-xs font-bold uppercase">Active</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 sm:gap-8 mb-8 sm:mb-10 border-b border-white/10 pb-8">
                        <div>
                            <p class="text-white/50 text-xs uppercase mb-2">Incident ID</p>
                            <p class="text-portal-md text-portal-leaf-tint font-bold font-mono">#NEMA-7392A</p>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs uppercase mb-2">Category</p>
                            <p class="text-portal-md font-bold">Illegal Logging</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-white/50 text-xs uppercase mb-2">Location Coordinate</p>
                            <p class="text-sm sm:text-base font-semibold">Mabira Forest Reserve (-0.3842, 32.9520)</p>
                        </div>
                    </div>
                    <div class="space-y-6 sm:space-y-8">
                        <div class="flex gap-4">
                            <div class="w-6 h-6 bg-portal-secondary-fixed text-portal-ink rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined filled text-xs">check</span>
                            </div>
                            <div>
                                <h5 class="text-sm font-semibold">1. Report Submitted</h5>
                                <p class="text-white/60 text-sm">Logged anonymously with coordinates and 3 photos.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-6 h-6 bg-portal-secondary-fixed text-portal-ink rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined filled text-xs">check</span>
                            </div>
                            <div>
                                <h5 class="text-sm font-semibold">2. Evidence Verified</h5>
                                <p class="text-white/60 text-sm">NEMA officers reviewed logs and cross-referenced satellite telemetry.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-6 h-6 border-2 border-portal-secondary-fixed text-portal-secondary-fixed rounded-full flex items-center justify-center shrink-0">
                                <span class="w-2 h-2 bg-portal-secondary-fixed rounded-full"></span>
                            </div>
                            <div>
                                <h5 class="text-sm font-semibold text-portal-secondary-fixed">3. Enforcement Dispatched</h5>
                                <p class="text-white/60 text-sm">Rangers deployed to local sector grids to secure boundaries.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-6 h-6 border-2 border-white/20 rounded-full flex items-center justify-center shrink-0"></div>
                            <div class="opacity-40">
                                <h5 class="text-sm font-semibold">4. Case Resolved</h5>
                                <p class="text-white/60 text-sm">Formal resolution report to be generated.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 sm:py-24 bg-portal-ink text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[60%] bg-portal-leaf-tint blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-portal-secondary blur-[120px] rounded-full"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-portal-margin-mobile welcome-reveal">
            <h2 class="text-2xl sm:text-portal-lg font-bold mb-6">Ready to Protect Uganda's Natural Heritage?</h2>
            <p class="text-base sm:text-portal-body-lg text-white/80 mb-10 sm:mb-12">
                Your contribution as a whistleblower is vital. Join thousands of citizens who are already helping NEMA monitor and protect our environment through the eCRMS portal.
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-4 sm:gap-6">
                <a href="{{ route('report.anonymous') }}" class="welcome-cta bg-portal-secondary-fixed text-portal-ink px-10 sm:px-12 py-4 sm:py-5 rounded-xl text-portal-md font-bold shadow-lg hover:scale-105 active:scale-95 transition-all no-underline">
                    Report a Crime Now
                </a>
                <a href="mailto:ecrms@nema.go.ug" class="welcome-cta bg-white/10 backdrop-blur-md border border-white/20 text-white px-10 sm:px-12 py-4 sm:py-5 rounded-xl text-portal-md font-bold hover:bg-white/20 transition-all no-underline">
                    Contact Inspectorate
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer id="contact" class="bg-portal-ink border-t border-white/10 pt-16 sm:pt-20 pb-8 sm:pb-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 px-4 sm:px-8 lg:px-portal-margin-desktop max-w-portal-container mx-auto text-white">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <span class="text-xl" aria-hidden="true">🌿</span>
                <span class="text-lg font-bold">NEMA <span class="text-portal-secondary-fixed">eCRMS</span></span>
            </div>
            <p class="text-sm text-portal-on-primary-container/80 leading-relaxed">
                Protecting Uganda's environment through state-of-the-art secure crime reporting and transparent case tracking systems.
            </p>
            <div class="flex items-center gap-3 text-portal-secondary-fixed">
                <span class="material-symbols-outlined filled text-base">mail</span>
                <a class="text-sm font-semibold hover:underline text-portal-secondary-fixed no-underline" href="mailto:ecrms@nema.go.ug">ecrms@nema.go.ug</a>
            </div>
        </div>
        <div>
            <h4 class="text-base font-bold mb-6 sm:mb-8">Quick Links</h4>
            <ul class="space-y-3 sm:space-y-4">
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="{{ route('home') }}">Home</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="{{ route('report.anonymous') }}">Report a Crime</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="{{ route('report.track') }}">Track Case</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="{{ route('login') }}">Citizen Log In</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="{{ route('register') }}">Create Account</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-base font-bold mb-6 sm:mb-8">Resources</h4>
            <ul class="space-y-3 sm:space-y-4">
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="#">Help &amp; FAQ</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="#">Whistleblower Protection</a></li>
                <li><a class="text-portal-secondary-fixed font-bold text-sm no-underline" href="#">Privacy Policy</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="#">Terms of Service</a></li>
                <li><a class="text-portal-on-primary-container/80 hover:text-portal-secondary-fixed transition-colors text-sm no-underline" href="#">Accessibility</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-base font-bold mb-6 sm:mb-8">Connect</h4>
            <div class="flex gap-3">
                <a class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-all" href="#" aria-label="Website">
                    <span class="material-symbols-outlined text-white text-xl">public</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-all" href="#" aria-label="Share">
                    <span class="material-symbols-outlined text-white text-xl">share</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-all" href="#" aria-label="Podcasts">
                    <span class="material-symbols-outlined text-white text-xl">podcasts</span>
                </a>
            </div>
        </div>
    </div>
    <div class="max-w-portal-container mx-auto px-4 sm:px-8 lg:px-portal-margin-desktop border-t border-white/5 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-sm text-portal-on-primary-container/60 text-center md:text-left">
            &copy; {{ date('Y') }} National Environment Management Authority (NEMA). All rights reserved. Secure &amp; Anonymous Reporting.
        </p>
        <span class="text-xs text-portal-on-primary-container/40 uppercase tracking-widest flex items-center gap-2">
            <span class="material-symbols-outlined filled text-xs">shield</span>
            ISO 27001 Certified
        </span>
    </div>
</footer>

</div>

<script defer>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-8');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.welcome-reveal, .welcome-bento-card').forEach((el) => {
            el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-8');
            observer.observe(el);
        });
    });
</script>
</body>
</html>

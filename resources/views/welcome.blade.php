<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ECRMS') }} — Environmental Crime Reporting & Monitoring System</title>
    <meta name="description" content="Report environmental crimes safely and anonymously. NEMA eCRMS helps citizens protect Uganda's environment through encrypted, secure crime reporting.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .card { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased font-sans text-gray-900 overflow-x-hidden">

<!-- Decorative Background Blobs -->
<div class="fixed w-[480px] h-[480px] rounded-full pointer-events-none -top-20 -left-32 z-0" style="background: radial-gradient(circle, rgba(94, 139, 61, 0.12) 0%, transparent 70%);"></div>
<div class="fixed w-[480px] h-[480px] rounded-full pointer-events-none -bottom-0 -right-32 z-0" style="background: radial-gradient(circle, rgba(94, 139, 61, 0.12) 0%, transparent 70%);"></div>

<!-- ━━━━ NAVIGATION BAR ━━━━ -->
<header class="fixed top-0 left-0 w-full z-[1000] transition-all duration-300" style="background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(18px); border-bottom: 1px solid rgba(94, 139, 61, 0.15); box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div class="max-w-screen-xl mx-auto flex items-center justify-between px-8 py-[0.6rem]">
        <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline font-extrabold text-lg text-gray-900 tracking-tight">
            🌿 NEMA <span class="text-[#5E8B3D]">eCRMS</span>
        </a>
        <nav class="flex items-center gap-8">
            <a href="{{ route('report.anonymous') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D]">Report Crime</a>
            <a href="{{ route('report.track') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D]">Track Case</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ auth()->user()->isCitizen() ? route('citizen.dashboard') : route('officer.dashboard') }}" class="inline-flex items-center justify-center px-[1.4rem] py-[0.6rem] rounded-full font-semibold text-sm no-underline border-2 border-[#5E8B3D] text-[#5E8B3D] bg-transparent transition-all duration-300 hover:bg-[#5E8B3D] hover:text-white hover:-translate-y-0.5">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="font-semibold text-sm cursor-pointer rounded-full px-[1.4rem] py-[0.6rem] transition-all duration-300 border-1.5" style="background: rgba(220, 53, 69, 0.08); color: #c0392b; border: 1.5px solid rgba(220, 53, 69, 0.3);" onmouseover="this.style.background='#dc3545'; this.style.color='#fff'; this.style.borderColor='#dc3545'; this.style.transform='translateY(-0.125rem)';" onmouseout="this.style.background='rgba(220, 53, 69, 0.08)'; this.style.color='#c0392b'; this.style.borderColor='rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(0)';">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D]">Log in</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-[1.4rem] py-[0.6rem] rounded-full font-semibold text-sm no-underline bg-[#5E8B3D] text-white border-2 border-[#5E8B3D] transition-all duration-300 hover:bg-[#3F6B2A] hover:-translate-y-0.5" style="box-shadow: 0 2px 8px rgba(94, 139, 61, 0.3);">Register</a>
            @endauth
        </div>
    </div>
</header>

<!-- ━━━━ HERO SECTION ━━━━ -->
<section class="w-full min-h-[92vh] flex flex-col items-center justify-center text-center px-8 pt-36 pb-28 bg-cover bg-center bg-no-repeat relative overflow-hidden" style="background-image: url('{{ asset('images/Hero-section.jpg') }}'); animation: fadeInUp 0.8s ease-out;">
    <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(63, 107, 42, 0.55) 0%, rgba(30, 60, 15, 0.50) 100%);"></div>
    <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-white font-bold text-[0.78rem] uppercase tracking-[1.2px] mb-7 px-5 py-[0.45rem] rounded-full" style="background: rgba(255, 255, 255, 0.18); border: 1.5px solid rgba(255, 255, 255, 0.35); backdrop-filter: blur(8px);">
            🛡️ Safe &amp; Fully Encrypted Reporting
        </div>
        <h1 class="text-[3.6rem] font-extrabold leading-[1.15] max-w-[860px] mb-3 tracking-[-1px] text-white">Safe &amp; Fully Encrypted Reporting</h1>
        <p class="text-[1.3rem] font-semibold mb-4" style="color: rgba(255, 255, 255, 0.92);">Report Environmental Crimes Safely &amp; Anonymously</p>
        <p class="text-[1.05rem] max-w-[660px] mb-10 font-normal" style="color: rgba(255, 255, 255, 0.85);">Submit illegal logging, wetland encroachment, pollution, and other environmental violations. Track your case from report to resolution.</p>
        <div class="flex gap-5 flex-wrap justify-center">
            <a href="{{ route('report.anonymous') }}" class="inline-flex items-center justify-center px-10 py-[0.9rem] text-base rounded-full font-semibold text-white no-underline border-2 bg-[#5E8B3D] transition-all duration-300 hover:-translate-y-0.5" style="border-color: #5E8B3D; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);" onmouseover="this.style.background='#3F6B2A'; this.style.borderColor='#3F6B2A';" onmouseout="this.style.background='#5E8B3D'; this.style.borderColor='#5E8B3D';">Report a Crime Now</a>
            <a href="{{ route('report.track') }}" class="inline-flex items-center justify-center px-10 py-[0.9rem] text-base rounded-full font-semibold no-underline border-2 transition-all duration-300 hover:-translate-y-0.5 text-white" style="background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px);" onmouseover="this.style.background='rgba(255, 255, 255, 0.30)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)';">Track a Case</a>
        </div>
    </div>
</section>

<!-- ━━━━ MAIN CONTENT ━━━━ -->
<div class="max-w-screen-xl mx-auto px-8 pb-20 relative z-[2]">

    <!-- Flash Messages -->
    <div style="max-width: 800px; margin: 2.5rem auto; width: 100%;">
        @include('partials.flash')
    </div>

    <!-- ━━━━ FEATURES SECTION ━━━━ -->
    <section class="pt-20 pb-12">
        <div class="text-center mb-14">
            <h2 class="text-[2.2rem] font-extrabold mb-[0.85rem] tracking-[-0.5px] text-[#5E8B3D]">System Features</h2>
            <p class="text-[#5E8B3D] max-w-[580px] mx-auto text-base">Designed with absolute security and a simple workflow to empower citizens and fast-track environmental protection.</p>
        </div>
        <div class="grid gap-7" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">

            <!-- Feature 1: Submit with Evidence -->
            <div class="bg-white rounded-2xl shadow-lg relative overflow-hidden hover:shadow-xl hover:-translate-y-2 hover:scale-[1.015] transition-all duration-300 p-9 border border-gray-100" style="border-color: rgba(94, 139, 61, 0.15);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-[1.4rem] text-[#5E8B3D] transition-all" style="background: rgba(94, 139, 61, 0.10); border: 1px solid rgba(94, 139, 61, 0.20);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-[0.6rem] text-gray-900">Submit with Evidence</h3>
                <p class="text-gray-500 text-[0.92rem] leading-relaxed">Easily upload photos, coordinates, videos, and full descriptions of the violation.</p>
            </div>

            <!-- Feature 2: Anonymous Tracking -->
            <div class="bg-white rounded-2xl shadow-lg relative overflow-hidden hover:shadow-xl hover:-translate-y-2 hover:scale-[1.015] transition-all duration-300 p-9 border border-gray-100" style="border-color: rgba(94, 139, 61, 0.15);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-[1.4rem] text-[#5E8B3D] transition-all" style="background: rgba(94, 139, 61, 0.10); border: 1px solid rgba(94, 139, 61, 0.20);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-[0.6rem] text-gray-900">Anonymous Tracking</h3>
                <p class="text-gray-500 text-[0.92rem] leading-relaxed">Never worry about safety. Get a fully secure, hash-generated tracking code to monitor progress.</p>
            </div>

            <!-- Feature 3: NEMA Verified -->
            <div class="bg-white rounded-2xl shadow-lg relative overflow-hidden hover:shadow-xl hover:-translate-y-2 hover:scale-[1.015] transition-all duration-300 p-9 border border-gray-100" style="border-color: rgba(94, 139, 61, 0.15);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-[1.4rem] text-[#5E8B3D] transition-all" style="background: rgba(94, 139, 61, 0.10); border: 1px solid rgba(94, 139, 61, 0.20);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-[0.6rem] text-gray-900">NEMA Verified</h3>
                <p class="text-gray-500 text-[0.92rem] leading-relaxed">Reports are forwarded instantly to regional enforcement and environment field teams.</p>
            </div>

            <!-- Feature 4: Real-time Updates -->
            <div class="bg-white rounded-2xl shadow-lg relative overflow-hidden hover:shadow-xl hover:-translate-y-2 hover:scale-[1.015] transition-all duration-300 p-9 border border-gray-100" style="border-color: rgba(94, 139, 61, 0.15);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-[1.4rem] text-[#5E8B3D] transition-all" style="background: rgba(94, 139, 61, 0.10); border: 1px solid rgba(94, 139, 61, 0.20);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <h3 class="text-[1.15rem] font-bold mb-[0.6rem] text-gray-900">Real-time Updates</h3>
                <p class="text-gray-500 text-[0.92rem] leading-relaxed">Receive notifications and review progress updates until the incident has been resolved.</p>
            </div>

        </div>
    </section>
</div><!-- /.main-wrapper -->

<!-- ━━━━ WHY REPORT SECTION (cream background) ━━━━ -->
<div class="w-screen py-20 px-8" style="background: #F3F5EA; margin-left: calc(-50vw + 50%);">
    <div class="max-w-screen-xl mx-auto grid gap-16 items-center" style="grid-template-columns: 1.1fr 0.9fr;">

        <!-- Left: Text & Bullets -->
        <div class="">
            <h2 class="text-[2.2rem] font-extrabold mb-4 leading-[1.25] text-gray-900">Why Report Through NEMA eCRMS?</h2>
            <p class="text-base text-gray-500 mb-8">Join citizens protecting Uganda's environment. Every report helps stop illegal logging, wetland destruction, and pollution.</p>
            <div class="flex flex-col gap-[1.1rem]">

                <div class="flex items-start gap-[0.9rem] bg-white rounded-2xl shadow-md px-5 py-4 border border-gray-100 hover:shadow-lg hover:translate-x-1 transition-all" style="border-color: rgba(94, 139, 61, 0.15);">
                    <div class="text-[#5E8B3D] flex-shrink-0 mt-[0.15rem] rounded-full flex items-center justify-center w-[34px] h-[34px]" style="background: rgba(94, 139, 61, 0.10); padding: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <strong class="text-gray-900 block mb-[0.2rem]">Strict Identity Protection</strong>
                        <p class="m-0 text-[0.92rem] text-gray-500">We use encryption protocols to scrub metadata from your uploads and files.</p>
                    </div>
                </div>

                <div class="flex items-start gap-[0.9rem] bg-white rounded-2xl shadow-md px-5 py-4 border border-gray-100 hover:shadow-lg hover:translate-x-1 transition-all" style="border-color: rgba(94, 139, 61, 0.15);">
                    <div class="text-[#5E8B3D] flex-shrink-0 mt-[0.15rem] rounded-full flex items-center justify-center w-[34px] h-[34px]" style="background: rgba(94, 139, 61, 0.10); padding: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <strong class="text-gray-900 block mb-[0.2rem]">Fast Response Routing</strong>
                        <p class="m-0 text-[0.92rem] text-gray-500">Automatically alerts the closest local authority or NEMA forest ranger unit.</p>
                    </div>
                </div>

                <div class="flex items-start gap-[0.9rem] bg-white rounded-2xl shadow-md px-5 py-4 border border-gray-100 hover:shadow-lg hover:translate-x-1 transition-all" style="border-color: rgba(94, 139, 61, 0.15);">
                    <div class="text-[#5E8B3D] flex-shrink-0 mt-[0.15rem] rounded-full flex items-center justify-center w-[34px] h-[34px]" style="background: rgba(94, 139, 61, 0.10); padding: 0.35rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <strong class="text-gray-900 block mb-[0.2rem]">Transparent Case Progress</strong>
                        <p class="m-0 text-[0.92rem] text-gray-500">View verification states, assigned officer notes, and field action dates.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: Active Case Live-Feed -->
        <div class="text-white rounded-[1.75rem] p-8 relative card" style="background: #3F6B2A; border: 1px solid rgba(255, 255, 255, 0.10); box-shadow: 0 20px 25px rgba(0,0,0,0.15);">
            <div class="flex justify-between items-center pb-4 mb-[1.4rem]" style="border-bottom: 1px solid rgba(255, 255, 255, 0.12);">
                <div>
                    <div class="text-base font-bold text-white">Active Case Live-Feed</div>
                    <div class="text-[0.75rem] mt-[0.2rem]" style="color: rgba(255,255,255,0.60);">Secure Telemetry Tracking View</div>
                </div>
                <div class="inline-flex items-center gap-[0.35rem] text-[0.72rem] font-bold uppercase tracking-[0.6px] px-3 py-[0.3rem] rounded-full" style="background: rgba(94, 220, 90, 0.18); border: 1px solid rgba(94, 220, 90, 0.50); color: #7deb7a;">
                    <span class="w-[6px] h-[6px] rounded-full bg-[#7deb7a] animate-pulse"></span> Active
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                <div class="mb-[1.1rem]">
                    <div class="text-[0.7rem] uppercase tracking-[0.6px] mb-[0.3rem]" style="color: rgba(255, 255, 255, 0.50);">Incident ID</div>
                    <div class="text-[0.9rem] font-semibold text-white" style="font-family:monospace; color:#a3e079;">#NEMA-7392A</div>
                </div>
                <div class="mb-[1.1rem]">
                    <div class="text-[0.7rem] uppercase tracking-[0.6px] mb-[0.3rem]" style="color: rgba(255, 255, 255, 0.50);">Category</div>
                    <div class="text-[0.9rem] font-semibold text-white">Illegal Logging</div>
                </div>
            </div>

            <div class="mb-[1.4rem]">
                <div class="text-[0.7rem] uppercase tracking-[0.6px] mb-[0.3rem]" style="color: rgba(255, 255, 255, 0.50);">Location Coordinate</div>
                <div class="text-[0.9rem] font-semibold text-white">Mabira Forest Reserve (-0.3842, 32.9520)</div>
            </div>

            <div class="flex flex-col gap-[1.1rem] mt-[1.4rem]">
                <!-- Step 1 -->
                <div class="flex gap-[0.9rem] items-start">
                    <div class="w-[22px] h-[22px] rounded-full flex items-center justify-center flex-shrink-0 text-[#0f2d0f]" style="background: #7deb7a;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <div class="text-[0.85rem] font-bold text-white">1. Report Submitted</div>
                        <div class="text-[0.75rem] mt-[0.1rem]" style="color: rgba(255,255,255,0.50);">Logged anonymously with coordinates and 3 photos.</div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-[0.9rem] items-start">
                    <div class="w-[22px] h-[22px] rounded-full flex items-center justify-center flex-shrink-0 text-[#0f2d0f]" style="background: #7deb7a;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <div class="text-[0.85rem] font-bold text-white">2. Evidence Verified</div>
                        <div class="text-[0.75rem] mt-[0.1rem]" style="color: rgba(255,255,255,0.50);">NEMA officers reviewed logs and cross-referenced satellite telemetry.</div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-[0.9rem] items-start">
                    <div class="w-[22px] h-[22px] rounded-full flex items-center justify-center flex-shrink-0 bg-transparent" style="border: 2px solid #7deb7a; box-shadow: 0 0 8px rgba(125, 235, 122, 0.40);">
                        <span style="width:8px;height:8px;background:#7deb7a;border-radius:50%;display:block;"></span>
                    </div>
                    <div class="flex-grow">
                        <div class="text-[0.85rem] font-bold" style="color: #7deb7a;">3. Enforcement Dispatched</div>
                        <div class="text-[0.75rem] mt-[0.1rem]" style="color: rgba(255,255,255,0.50);">Rangers deployed to local sector grids to secure boundaries.</div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-[0.9rem] items-start">
                    <div class="w-[22px] h-[22px] rounded-full flex-shrink-0" style="background: rgba(255, 255, 255, 0.08); border: 2px solid rgba(255, 255, 255, 0.20);"></div>
                    <div class="flex-grow">
                        <div class="text-[0.85rem] font-bold" style="color: rgba(255, 255, 255, 0.35);">4. Case Resolved</div>
                        <div class="text-[0.75rem] mt-[0.1rem]" style="color: rgba(255,255,255,0.28);">Formal resolution report to be generated.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>

    </div>
</div>

<!-- ━━━━ FOOTER ━━━━ -->
<footer id="contact" class="bg-[#3F6B2A] pt-14 pb-7 px-8 relative z-[2]">
    <div class="max-w-screen-xl mx-auto grid gap-12 mb-10" style="grid-template-columns: 1.6fr repeat(2, 1fr);">

        <div class="">
            <h3 class="text-[1.3rem] font-extrabold mb-3 text-white">🌿 NEMA <span style="color: #a3e079;">eCRMS</span></h3>
            <p class="text-[0.88rem] max-w-[300px] leading-[1.65]" style="color: rgba(255, 255, 255, 0.65);">Protecting Uganda's environment through state-of-the-art secure crime reporting and transparent case tracking systems.</p>
            <a href="mailto:ecrms@nema.go.ug" class="inline-flex items-center gap-[0.4rem] mt-4 text-[0.88rem] no-underline font-semibold transition-colors duration-200" style="color: #a3e079;" onmouseover="this.style.color='white';" onmouseout="this.style.color='#a3e079';">
                📧 ecrms@nema.go.ug
            </a>
        </div>

        <div class="">
            <h4 class="text-[0.85rem] font-bold mb-[1.1rem] uppercase tracking-[0.7px]" style="color: rgba(255, 255, 255, 0.90);">Quick Links</h4>
            <div class="flex flex-col gap-[0.65rem]">
                <a href="{{ route('home') }}" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Home</a>
                <a href="{{ route('report.anonymous') }}" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Report a Crime</a>
                <a href="{{ route('report.track') }}" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Track Case</a>
                <a href="{{ route('login') }}" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Log In Portal</a>
                <a href="{{ route('register') }}" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Create Account</a>
            </div>
        </div>

        <div class="">
            <h4 class="text-[0.85rem] font-bold mb-[1.1rem] uppercase tracking-[0.7px]" style="color: rgba(255, 255, 255, 0.90);">Contact</h4>
            <div class="flex flex-col gap-[0.65rem]">
                <a href="mailto:ecrms@nema.go.ug" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">ecrms@nema.go.ug</a>
                <a href="#" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Help &amp; FAQ</a>
                <a href="#" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Privacy Policy</a>
                <a href="#" class="no-underline text-[0.875rem] transition-all duration-[250ms] pl-0" style="color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.color='#a3e079'; this.style.paddingLeft='5px';" onmouseout="this.style.color='rgba(255, 255, 255, 0.55)'; this.style.paddingLeft='0';">Terms of Service</a>
            </div>
        </div>

    </div>

    <div class="max-w-screen-xl mx-auto flex justify-between items-center flex-wrap gap-4 pt-6" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="text-[0.82rem]" style="color: rgba(255, 255, 255, 0.45);">
            &copy; {{ date('Y') }} NEMA eCRMS – Protecting Uganda's Environment. All Rights Reserved.
        </div>
        <div class="flex gap-3">
            <a href="#" class="w-[34px] h-[34px] rounded-full flex items-center justify-center no-underline transition-all duration-[250ms] text-white" style="background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.background='#5E8B3D'; this.style.color='white'; this.style.transform='translateY(-0.125rem)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.color='rgba(255, 255, 255, 0.55)'; this.style.transform='translateY(0)';" aria-label="Facebook">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                </svg>
            </a>
            <a href="#" class="w-[34px] h-[34px] rounded-full flex items-center justify-center no-underline transition-all duration-[250ms] text-white" style="background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.background='#5E8B3D'; this.style.color='white'; this.style.transform='translateY(-0.125rem)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.color='rgba(255, 255, 255, 0.55)'; this.style.transform='translateY(0)';" aria-label="Twitter">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                </svg>
            </a>
            <a href="#" class="w-[34px] h-[34px] rounded-full flex items-center justify-center no-underline transition-all duration-[250ms] text-white" style="background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.55);" onmouseover="this.style.background='#5E8B3D'; this.style.color='white'; this.style.transform='translateY(-0.125rem)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.color='rgba(255, 255, 255, 0.55)'; this.style.transform='translateY(0)';" aria-label="Instagram">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
            </a>
        </div>
    </div>
</footer>

<script>
    // Subtle mouse parallax on the mock case tracker
    const mockTracker = document.querySelector('.mock-tracker');
    if (mockTracker) {
        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 10;
            const y = (e.clientY / window.innerHeight - 0.5) * 10;
            mockTracker.style.transform = `translateY(${y * -0.5}px) translateX(${x * 0.25}px) rotate(${x * 0.04}deg)`;
        });
    }
</script>

</body>
</html>

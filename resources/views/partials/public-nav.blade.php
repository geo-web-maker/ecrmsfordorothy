@php
    $systemFullName = 'Environmental Crime Reporting & Monitoring System';
@endphp

@once
    @vite(['resources/css/welcome-effects.css', 'resources/js/public-nav-effects.js'])
@endonce

<style>
    .public-mobile-drawer {
        background-color: #ffffff !important;
    }
    .public-mobile-drawer nav a,
    .public-mobile-drawer nav a:link,
    .public-mobile-drawer nav a:visited {
        color: #1F3318 !important;
    }
    .public-mobile-drawer nav a:hover {
        color: #3F6B2A !important;
        background-color: #F3F5EA !important;
    }
    .public-mobile-drawer .drawer-section-label {
        color: #7B8F69 !important;
    }
    body.public-nav-open {
        overflow: hidden;
    }
    [x-cloak] { display: none !important; }

    @keyframes attractRegister {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94,139,61,0.4); }
        50% { transform: scale(1.05); box-shadow: 0 0 20px 5px rgba(94,139,61,0.3); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94,139,61,0); }
    }

    .register-btn {
        animation: attractRegister 2.5s ease-in-out infinite;
    }

    .register-btn:hover {
        animation-play-state: paused;
    }

    @keyframes shakeNudge {
        0%, 100% { transform: translateX(0); }
        10% { transform: translateX(-6px); }
        20% { transform: translateX(6px); }
        30% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        50% { transform: translateX(-3px); }
        60% { transform: translateX(3px); }
        70% { transform: translateX(0); }
    }

    .navbar-logo {
        animation: shakeNudge 3.5s ease-in-out infinite;
        display: inline-flex;
    }

    .navbar-logo:hover {
        animation: none;
    }
</style>

<div
    x-data="{ open: false }"
    x-effect="document.body.classList.toggle('public-nav-open', open)"
    @keydown.escape.window="open = false"
    class="public-nav-shell relative z-[10000]"
>
    <header
        id="public-nav-header"
        class="public-nav-header fixed top-0 left-0 w-full z-[10000] transition-all duration-300"
        style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(18px); border-bottom: 1px solid rgba(94, 139, 61, 0.15); box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
    >
        <div class="max-w-screen-xl mx-auto flex items-center justify-between gap-4 px-5 sm:px-8 py-3">
            <a href="{{ route('home') }}" class="navbar-logo flex items-center gap-2 no-underline font-extrabold text-lg text-gray-900 tracking-tight min-w-0">
                <span class="logo-pip" aria-hidden="true"></span>
                <span class="text-xl flex-shrink-0" aria-hidden="true">🌿</span>
                <span class="hidden md:inline">NEMA <span class="text-[#5E8B3D]">eCRMS</span></span>
                <span class="md:hidden min-w-0">
                    <span class="block font-extrabold text-sm leading-tight">NEMA <span class="text-[#5E8B3D]">eCRMS</span></span>
                    <span class="block text-[0.62rem] font-semibold text-[#7B8F69] leading-snug mt-0.5">{{ $systemFullName }}</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="no-underline font-semibold text-sm transition-colors {{ request()->routeIs('home') ? 'text-[#5E8B3D] font-bold' : 'text-gray-600 hover:text-[#5E8B3D]' }}">Home</a>
                <a href="{{ route('report.anonymous') }}" class="no-underline font-semibold text-sm transition-colors {{ request()->routeIs('report.anonymous*') ? 'text-[#5E8B3D] font-bold' : 'text-gray-600 hover:text-[#5E8B3D]' }}">Report Crime</a>
                <a href="{{ route('report.track') }}" class="no-underline font-semibold text-sm transition-colors {{ request()->routeIs('report.track') || request()->routeIs('report.tracking.result') ? 'text-[#5E8B3D] font-bold' : 'text-gray-600 hover:text-[#5E8B3D]' }}">Track Case</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ auth()->user()->isCitizen() ? route('citizen.dashboard') : route('officer.dashboard') }}" class="inline-flex items-center justify-center px-5 py-2 rounded-full font-semibold text-sm no-underline border-2 border-[#5E8B3D] text-[#5E8B3D] transition-all hover:bg-[#5E8B3D] hover:text-white">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold text-sm cursor-pointer rounded-full px-5 py-2 transition-all" style="background: rgba(220, 53, 69, 0.08); color: #c0392b; border: 1.5px solid rgba(220, 53, 69, 0.3);">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D] {{ request()->routeIs('login') ? 'text-[#5E8B3D]' : '' }}">Log in</a>
                    <a href="{{ route('register') }}" class="register-btn inline-flex items-center justify-center px-5 py-2 rounded-full font-semibold text-sm no-underline bg-[#5E8B3D] text-white border-2 border-[#5E8B3D] transition-all hover:bg-[#3F6B2A] {{ request()->routeIs('register') ? 'ring-2 ring-[#5E8B3D]/30' : '' }}">Register</a>
                @endauth
            </div>

            <button
                type="button"
                @click="open = !open"
                class="md:hidden relative inline-flex items-center justify-center w-11 h-11 rounded-2xl border border-[rgba(94,139,61,0.25)] bg-white text-[#3F6B2A] shadow-sm transition-all hover:bg-[#F3F5EA] focus:outline-none focus:ring-2 focus:ring-[#5E8B3D]/30"
                :aria-expanded="open"
                :aria-label="open ? 'Close menu' : 'Open menu'"
            >
                <span class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open ? 'opacity-0 scale-75 rotate-90' : 'opacity-100 scale-100 rotate-0'">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                        <line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>
                    </svg>
                </span>
                <span class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open ? 'opacity-100 scale-100 rotate-0' : 'opacity-0 scale-75 -rotate-90'">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <div
        x-show="open"
        x-cloak
        @click="open = false"
        class="fixed inset-0 z-[10001] md:hidden"
        style="background-color: rgba(13, 21, 11, 0.55); backdrop-filter: blur(2px);"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <div
        x-show="open"
        x-cloak
        class="public-mobile-drawer fixed top-0 right-0 z-[10002] h-full w-[min(320px,88vw)] md:hidden flex flex-col shadow-[-8px_0_40px_rgba(15,35,10,0.22)]"
        style="background-color: #ffffff; border-left: 1px solid rgba(94, 139, 61, 0.15);"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-250 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
    >
        <div class="flex items-center justify-between px-5 py-4 border-b" style="background: #ffffff; border-color: rgba(94,139,61,0.12);">
            <p class="text-sm font-extrabold m-0" style="color: #3F6B2A;">NEMA <span style="color: #5E8B3D;">eCRMS</span></p>
            <button type="button" @click="open = false" class="flex items-center justify-center w-10 h-10 rounded-xl border transition hover:bg-[#F3F5EA]" style="background: #fff; color: #3F6B2A; border-color: rgba(94,139,61,0.2);" aria-label="Close menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <nav class="flex flex-col flex-1 overflow-y-auto px-5 py-6" style="background: #ffffff;">
            <p class="drawer-section-label text-[0.65rem] font-bold uppercase tracking-[0.14em] px-3 mb-3 m-0">Navigate</p>
            <a href="{{ route('home') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center {{ request()->routeIs('home') ? 'bg-[#F3F5EA]' : '' }}" style="color: #1F3318;">Home</a>
            <a href="{{ route('report.anonymous') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center mt-1" style="color: #1F3318;">Report a Crime</a>
            <a href="{{ route('report.track') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center mt-1" style="color: #1F3318;">Track a Case</a>

            @auth
                <p class="drawer-section-label text-[0.65rem] font-bold uppercase tracking-[0.14em] px-3 mb-3 mt-6 m-0">Account</p>
                <a href="{{ auth()->user()->isCitizen() ? route('citizen.dashboard') : route('officer.dashboard') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center" style="color: #1F3318;">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4 px-3">
                    @csrf
                    <button type="submit" class="w-full font-semibold text-sm cursor-pointer rounded-full px-5 py-3 transition-all" style="background: rgba(220, 53, 69, 0.08); color: #c0392b; border: 1.5px solid rgba(220, 53, 69, 0.3);">Logout</button>
                </form>
            @else
                <p class="drawer-section-label text-[0.65rem] font-bold uppercase tracking-[0.14em] px-3 mb-3 mt-6 m-0">Account</p>
                <a href="{{ route('login') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center {{ request()->routeIs('login') ? 'bg-[#F3F5EA]' : '' }}" style="color: #1F3318;">Log in</a>
                <a href="{{ route('register') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center mt-1 {{ request()->routeIs('register') ? 'bg-[#F3F5EA]' : '' }}" style="color: #1F3318;">Register</a>
            @endauth
        </nav>
    </div>
</div>

<div class="h-[56px] md:h-[52px]" aria-hidden="true"></div>

@include('partials.logout-confirm')

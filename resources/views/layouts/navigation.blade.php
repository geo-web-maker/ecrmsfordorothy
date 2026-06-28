<div
    x-data="{ open: false }"
    x-effect="document.body.classList.toggle('public-nav-open', open)"
    @keydown.escape.window="open = false"
    class="app-nav-shell relative z-[10000]"
>
    <style>
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
    <nav style="background: white; border-bottom: 1px solid rgba(94,139,61,0.15); box-shadow: 0 1px 3px rgba(63,107,42,0.06);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="navbar-logo flex items-center gap-2 font-extrabold text-lg tracking-tight" style="text-decoration:none; color:#1F3318;">
                            🌿 NEMA <span style="color:#5E8B3D;">eCRMS</span>
                        </a>
                    </div>

                    <div class="hidden space-x-1 sm:ms-8 sm:flex">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors"
                           style="{{ request()->routeIs('dashboard') || request()->routeIs('citizen.*') || request()->routeIs('officer.*') ? 'background:#EAF1DD; color:#3F6B2A;' : 'color:#5F6B57;' }}">
                            Dashboard
                        </a>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:gap-4">
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-colors"
                                    style="color:#3F6B2A; background:rgba(94,139,61,0.08); border:1.5px solid rgba(94,139,61,0.25);">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold text-white" style="background:#3F6B2A;">
                                    {{ Auth::user()->initial }}
                                </span>
                                {{ Auth::user()->name }}
                                <svg class="w-4 h-4 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div style="padding:6px 12px 4px; font-size:10px; font-weight:700; color:#7B8F69; text-transform:uppercase; letter-spacing:1px;">
                                My Account
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                <span class="flex items-center gap-2 text-sm font-medium" style="color:#3F6B2A;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ __('Profile') }}
                                </span>
                            </x-dropdown-link>

                            <div style="height:1px; background:rgba(94,139,61,0.1); margin:4px 12px;"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full px-4 py-2.5 text-start text-sm leading-5 hover:bg-[#F3F5EA] focus:outline-none transition duration-150 ease-in-out border-none bg-transparent cursor-pointer">
                                    <span class="flex items-center gap-2 text-sm font-medium" style="color:#c0392b;">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        {{ __('Log Out') }}
                                    </span>
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="flex items-center sm:hidden">
                    <button
                        type="button"
                        @click="open = !open"
                        class="relative inline-flex items-center justify-center w-11 h-11 rounded-2xl border border-[rgba(94,139,61,0.25)] bg-white text-[#3F6B2A] shadow-sm transition-all hover:bg-[#F3F5EA] focus:outline-none focus:ring-2 focus:ring-[#5E8B3D]/30"
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
            </div>
        </div>
    </nav>

    <div
        x-show="open"
        x-cloak
        @click="open = false"
        class="fixed inset-0 z-[10001] sm:hidden"
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
        class="public-mobile-drawer fixed top-0 right-0 z-[10002] h-full w-[min(320px,88vw)] sm:hidden flex flex-col shadow-[-8px_0_40px_rgba(15,35,10,0.22)]"
        style="background-color: #ffffff; border-left: 1px solid rgba(94, 139, 61, 0.15);"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-250 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
    >
        <div class="flex items-center justify-between px-5 py-4 border-b" style="background: #ffffff; border-color: rgba(94,139,61,0.12);">
            <div class="flex items-center gap-3 min-w-0">
                <span class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold text-white flex-shrink-0" style="background:#3F6B2A;">
                    {{ Auth::user()->initial }}
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-bold m-0 truncate" style="color:#1F3318;">{{ Auth::user()->name }}</p>
                    <p class="text-xs m-0 truncate" style="color:#7B8F69;">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <button type="button" @click="open = false" class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl border transition hover:bg-[#F3F5EA]" style="background: #fff; color: #3F6B2A; border-color: rgba(94,139,61,0.2);" aria-label="Close menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <nav class="flex flex-col flex-1 overflow-y-auto px-5 py-6" style="background: #ffffff;">
            <p class="text-[0.65rem] font-bold uppercase tracking-[0.14em] px-3 mb-3 m-0" style="color:#7B8F69;">Menu</p>
            <a href="{{ route('dashboard') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center" style="color: #1F3318;">Dashboard</a>
            <a href="{{ route('profile.edit') }}" @click="open = false" class="no-underline font-semibold text-base py-3.5 px-3 rounded-xl transition-colors flex items-center mt-1" style="color: #1F3318;">Profile</a>

            <form method="POST" action="{{ route('logout') }}" class="mt-6 px-3">
                @csrf
                <button type="submit" class="w-full font-semibold text-sm cursor-pointer rounded-full px-5 py-3 transition-all" style="background: rgba(220, 53, 69, 0.08); color: #c0392b; border: 1.5px solid rgba(220, 53, 69, 0.3);">
                    Log Out
                </button>
            </form>
        </nav>
    </div>
</div>

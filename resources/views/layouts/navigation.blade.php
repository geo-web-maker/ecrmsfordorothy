<nav x-data="{ open: false }" style="background: white; border-bottom: 1px solid rgba(94,139,61,0.15); box-shadow: 0 1px 3px rgba(63,107,42,0.06);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-extrabold text-lg tracking-tight" style="text-decoration:none; color:#1F3318;">
                        🌿 NEMA <span style="color:#5E8B3D;">eCRMS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:ms-8 sm:flex">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors"
                       style="{{ request()->routeIs('dashboard') ? 'background:#EAF1DD; color:#3F6B2A;' : 'color:#5F6B57;' }}"
                       onmouseover="if(!this.classList.contains('active'))this.style.background='#F3F5EA';"
                       onmouseout="if(!{{ request()->routeIs('dashboard') ? 'true' : 'false' }})this.style.background='transparent';">
                        Dashboard
                    </a>
                </div>
            </div>

            <!-- Right side: User dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">

                <!-- User name + dropdown -->
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold transition-colors"
                                style="color:#3F6B2A; background:rgba(94,139,61,0.08); border:1.5px solid rgba(94,139,61,0.25);"
                                onmouseover="this.style.background='rgba(94,139,61,0.14)';"
                                onmouseout="this.style.background='rgba(94,139,61,0.08)';">
                            <span class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold text-white" style="background:#3F6B2A;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
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

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="flex items-center gap-2 text-sm font-medium" style="color:#c0392b;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    {{ __('Log Out') }}
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg transition-colors"
                        style="color:#5E8B3D; background:transparent;"
                        onmouseover="this.style.background='rgba(94,139,61,0.08)';"
                        onmouseout="this.style.background='transparent';">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="border-top:1px solid rgba(94,139,61,0.12); background:#FAFBF7;">
        <div class="pt-2 pb-3 px-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg text-sm font-semibold"
               style="{{ request()->routeIs('dashboard') ? 'background:#EAF1DD; color:#3F6B2A;' : 'color:#5F6B57;' }}">
                Dashboard
            </a>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-3 pb-4 border-t px-4 space-y-1" style="border-color:rgba(94,139,61,0.12);">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold text-white" style="background:#3F6B2A;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="text-sm font-semibold" style="color:#1F3318;">{{ Auth::user()->name }}</div>
                    <div class="text-xs" style="color:#7B8F69;">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium"
               style="color:#3F6B2A;">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium"
                        style="color:#c0392b; background:transparent; border:none; cursor:pointer;">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>

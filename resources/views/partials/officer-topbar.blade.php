<header class="w-full h-16 bg-white border-b border-portal-outline-variant flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40 shrink-0">
    <div class="flex items-center gap-3 min-w-0">
        <button type="button"
                class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg border border-portal-outline-variant text-portal-ink hover:bg-portal-surface transition-colors"
                @click="sidebarOpen = true"
                aria-label="Open menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="text-lg sm:text-xl font-bold text-portal-ink truncate">NEMA eCRMS</span>
        @if ($pageTitle ?? false)
            <span class="hidden sm:inline text-portal-on-surface-variant text-sm font-medium">/ {{ $pageTitle }}</span>
        @endif
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
        @if (request()->routeIs('officer.reports'))
            <form method="GET" action="{{ route('officer.reports') }}" class="hidden sm:block">
                @foreach (request()->except('q', 'page') as $key => $value)
                    @if (is_string($value) && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-portal-outline text-lg">search</span>
                    <input type="search" name="q" value="{{ request('q') }}"
                           class="pl-10 pr-4 py-2 bg-portal-surface border border-portal-outline-variant rounded-full text-sm focus:ring-2 focus:ring-portal-secondary focus:border-transparent outline-none w-48 lg:w-64 transition-all"
                           placeholder="Search report ID…">
                </div>
            </form>
        @endif

        <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-portal-outline-variant">
            <span class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold text-white bg-portal-secondary">
                {{ Auth::user()->initial }}
            </span>
            <span class="text-sm font-semibold text-portal-ink max-w-[120px] truncate hidden lg:inline">{{ Auth::user()->name }}</span>
        </div>
    </div>
</header>

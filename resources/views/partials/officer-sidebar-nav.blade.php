@php
    $activeNav = $activeNav ?? '';
    $isAdmin = Auth::user()->isAdmin();

    $navItem = function (string $key, string $label, string $icon, string $route, bool $filled = false) use ($activeNav) {
        $active = $activeNav === $key;

        return [
            'key'         => $key,
            'label'       => $label,
            'icon'        => $icon,
            'route'       => $route,
            'filled'      => $filled,
            'active'      => $active,
            'base'        => 'flex items-center px-4 py-3 mx-2 my-0.5 rounded-lg text-sm font-semibold transition-all no-underline',
            'activeClass' => 'bg-portal-secondary-container text-portal-on-secondary-container',
            'idleClass'   => 'text-white/70 hover:text-white hover:bg-white/5',
        ];
    };

    if ($isAdmin) {
        $items = [
            $navItem('dashboard', 'Executive View', 'dashboard', route('officer.dashboard')),
            $navItem('reports', 'Incident Queue', 'list_alt', route('officer.reports'), true),
            $navItem('map', 'Ranger Tracking', 'distance', route('officer.map')),
            $navItem('analytics', 'Analytics', 'monitoring', route('officer.analytics')),
        ];
    } else {
        $items = [
            $navItem('dashboard', 'My Overview', 'dashboard', route('officer.dashboard')),
            $navItem('reports', 'My Assigned Cases', 'list_alt', route('officer.reports'), true),
            $navItem('map', 'Incident Map', 'distance', route('officer.map')),
        ];
    }
@endphp

<div class="px-5 mb-8 flex flex-col items-start">
    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mb-3">
        <span class="material-symbols-outlined text-portal-ink text-3xl filled">security</span>
    </div>
    <h2 class="text-lg font-bold text-white m-0">{{ $isAdmin ? 'NEMA Admin' : 'NEMA Officer' }}</h2>
    <p class="text-xs text-white/70 m-0 mt-1">Authorized Personnel Only</p>
</div>

<nav class="flex-grow space-y-0.5 px-1">
    @foreach ($items as $item)
        <a href="{{ $item['route'] }}"
           @if ($sidebarClose ?? false) @click="sidebarOpen = false" @endif
           class="{{ $item['base'] }} {{ $item['active'] ? $item['activeClass'] : $item['idleClass'] }}">
            <span class="material-symbols-outlined mr-3 text-xl {{ $item['filled'] && $item['active'] ? 'filled' : '' }}">{{ $item['icon'] }}</span>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>

<div class="px-4 mt-auto pt-4 border-t border-white/10">
    <div class="space-y-1">
        <a href="{{ route('profile.edit') }}"
           @if ($sidebarClose ?? false) @click="sidebarOpen = false" @endif
           class="flex items-center px-3 py-2 text-sm font-semibold no-underline transition-colors rounded-lg {{ ($activeNav === 'account' || request()->routeIs('profile.edit')) ? 'bg-white/15 text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <span class="material-symbols-outlined mr-3 text-base {{ ($activeNav === 'account' || request()->routeIs('profile.edit')) ? 'filled' : '' }}">settings</span>
            Account Settings
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 text-white/70 hover:text-white text-sm font-semibold bg-transparent border-none cursor-pointer transition-colors rounded-lg hover:bg-white/5">
                <span class="material-symbols-outlined mr-3 text-base">logout</span>
                Log Out
            </button>
        </form>
    </div>
</div>

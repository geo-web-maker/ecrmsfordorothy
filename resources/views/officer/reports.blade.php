@php
    $priorityStyles = [
        'Urgent'   => 'bg-red-100 text-red-800',
        'Critical' => 'bg-red-100 text-red-800',
        'High'     => 'bg-portal-secondary-container text-portal-on-secondary-container',
        'Medium'   => 'bg-gray-200 text-gray-700',
        'Low'      => 'bg-gray-100 text-gray-600',
    ];
    $statusDots = [
        'Submitted'     => 'bg-yellow-500',
        'Under Review'  => 'bg-green-500',
        'Assigned'      => 'bg-blue-500',
        'Resolved'      => 'bg-emerald-600',
        'Closed'        => 'bg-gray-400',
    ];
    $categoryIcons = [
        'logging' => 'forest',
        'wetland' => 'water_drop',
        'pollution' => 'water_drop',
        'encroach' => 'fence',
        'poach' => 'pets',
    ];
    $resolveIcon = function (?string $name) use ($categoryIcons) {
        $lower = strtolower($name ?? '');
        foreach ($categoryIcons as $key => $icon) {
            if (str_contains($lower, $key)) {
                return $icon;
            }
        }
        return 'eco';
    };
@endphp

<x-officer-admin-layout active-nav="reports" :page-title="Auth::user()->isAdmin() ? 'Incident Queue' : 'My Assigned Cases'">
    <div class="p-4 sm:p-6 max-w-[1200px] mx-auto w-full space-y-6">
        @include('partials.flash')

        {{-- Page header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-portal-on-surface m-0">
                    {{ Auth::user()->isAdmin() ? 'Incident Queue' : 'My Assigned Cases' }}
                </h1>
                <p class="text-sm text-portal-on-surface-variant mt-1 mb-0">
                    Reviewing {{ number_format($totalCount) }} environmental protection report{{ $totalCount === 1 ? '' : 's' }}.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-portal-secondary bg-portal-secondary-container px-3 py-1.5 rounded-full border border-portal-secondary/20 self-start">
                <span class="material-symbols-outlined text-sm filled">verified_user</span>
                Secure Encrypted Session Active
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('officer.reports') }}" class="bg-white border border-portal-outline-variant p-4 sm:p-6 rounded-lg shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs font-semibold text-portal-on-surface-variant block mb-1">Category</label>
                    <select name="category" class="w-full border-portal-outline-variant rounded-lg text-sm focus:ring-portal-secondary focus:border-portal-secondary">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-portal-on-surface-variant block mb-1">Status</label>
                    <select name="status" class="w-full border-portal-outline-variant rounded-lg text-sm focus:ring-portal-secondary focus:border-portal-secondary">
                        <option value="">All Statuses</option>
                        @foreach (['Submitted', 'Under Review', 'Assigned', 'Resolved', 'Closed'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-portal-on-surface-variant block mb-1">Priority</label>
                    <select name="priority" class="w-full border-portal-outline-variant rounded-lg text-sm focus:ring-portal-secondary focus:border-portal-secondary">
                        <option value="">All Priorities</option>
                        @foreach (['Urgent', 'High', 'Medium', 'Low'] as $p)
                            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-grow h-11 bg-portal-ink text-white rounded-lg font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-lg">filter_list</span>
                        Apply Filters
                    </button>
                    <a href="{{ route('officer.reports') }}" class="w-11 h-11 border border-portal-outline-variant rounded-lg flex items-center justify-center text-portal-on-surface-variant hover:bg-portal-surface transition-colors" title="Clear filters">
                        <span class="material-symbols-outlined">refresh</span>
                    </a>
                </div>
            </div>
            <div class="mt-3 sm:hidden">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search report ID…"
                       class="w-full border-portal-outline-variant rounded-lg text-sm px-3 py-2 focus:ring-portal-secondary focus:border-portal-secondary">
            </div>
        </form>

        {{-- Incident table (desktop) + cards (mobile) --}}
        <div class="bg-white border border-portal-outline-variant rounded-lg overflow-hidden shadow-sm">
            {{-- Mobile: horizontal-style cards --}}
            <div class="md:hidden divide-y divide-portal-outline-variant">
                @forelse ($reports as $report)
                    @include('partials.officer-report-mobile-card', [
                        'report' => $report,
                        'showLocation' => true,
                        'priorityStyles' => $priorityStyles,
                        'statusDots' => $statusDots,
                        'resolveIcon' => $resolveIcon,
                    ])
                @empty
                    <p class="px-4 py-12 text-center text-sm text-portal-on-surface-variant m-0">
                        {{ Auth::user()->isOfficer() ? 'No cases have been assigned to you yet.' : 'No reports match your filters.' }}
                    </p>
                @endforelse
            </div>

            {{-- Desktop: table --}}
            <div class="hidden md:block overflow-x-auto officer-admin-scrollbar-hide">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-portal-surface border-b border-portal-outline-variant">
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">ID</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">Incident Type</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">Location</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">Timestamp</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">Priority</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-xs font-semibold text-portal-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-portal-outline-variant">
                        @forelse ($reports as $report)
                            <tr class="officer-incident-row hover:bg-portal-surface group">
                                <td class="px-4 sm:px-6 py-4 text-sm font-semibold text-portal-ink">#{{ $report->id }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-portal-secondary text-lg">{{ $resolveIcon($report->crimeCategory->name ?? '') }}</span>
                                        <span class="text-sm">{{ $report->crimeCategory->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm max-w-[160px] truncate">{{ $report->location_address ?? $report->crime->location ?? '—' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-portal-on-surface-variant whitespace-nowrap">{{ $report->created_at->format('M d, H:i') }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    @if ($report->priority)
                                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full uppercase {{ $priorityStyles[$report->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $report->priority === 'Urgent' ? 'Critical' : $report->priority }}
                                        </span>
                                    @else
                                        <span class="text-sm text-portal-on-surface-variant">—</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="flex items-center gap-1.5 text-sm text-portal-on-surface-variant whitespace-nowrap">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $statusDots[$report->status] ?? 'bg-gray-400' }}"></span>
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right">
                                    <a href="{{ route('officer.report.show', $report) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-semibold text-white bg-portal-secondary hover:bg-portal-ink transition-colors no-underline">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                        <span class="hidden sm:inline">View</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-portal-on-surface-variant">
                                    {{ Auth::user()->isOfficer() ? 'No cases have been assigned to you yet.' : 'No reports match your filters.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="px-4 sm:px-6 py-4 bg-portal-surface border-t border-portal-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3">
                    <span class="text-xs text-portal-on-surface-variant">
                        Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} entries
                    </span>
                    <div>{{ $reports->links() }}</div>
                </div>
            @endif
        </div>

        {{-- Map + Priority bento --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 h-72 sm:h-80 rounded-lg border border-portal-outline-variant overflow-hidden relative shadow-sm bg-slate-200">
                <div
                    id="incident-map-preview"
                    data-officer-map
                    data-marker-style="circle"
                    data-zoom-control="false"
                    data-map-markers='@json($mapMarkers)'
                    class="absolute inset-0 z-[1]"
                ></div>
                <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg border border-portal-outline-variant">
                    <p class="text-xs font-bold text-portal-ink m-0">Live Incident Map Overlay</p>
                </div>
                <div class="absolute bottom-4 right-4 z-10 flex gap-2">
                    <a href="{{ route('officer.map') }}" class="bg-portal-ink text-white p-2 rounded-full shadow-lg hover:scale-105 transition-transform no-underline" title="Open full map">
                        <span class="material-symbols-outlined">open_in_full</span>
                    </a>
                </div>
            </div>

            <div class="bg-portal-ink text-white p-5 sm:p-6 rounded-lg flex flex-col justify-between shadow-lg min-h-[280px]">
                <div>
                    <h3 class="text-xl font-bold m-0 mb-2">Priority Summary</h3>
                    <p class="text-sm text-white/80 m-0 mb-6">Real-time breakdown of alerts requiring attention.</p>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-widest text-white/70">Critical</span>
                            <span class="text-2xl font-bold">{{ $prioritySummary['critical'] }}</span>
                        </div>
                        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                            @php $critPct = $prioritySummary['total'] > 0 ? min(100, round(($prioritySummary['critical'] / $prioritySummary['total']) * 100)) : 0; @endphp
                            <div class="bg-red-500 h-full" style="width: {{ max($critPct, $prioritySummary['critical'] > 0 ? 8 : 0) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-widest text-white/70">High Response</span>
                            <span class="text-2xl font-bold">{{ $prioritySummary['high'] }}</span>
                        </div>
                        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                            @php $highPct = $prioritySummary['total'] > 0 ? min(100, round(($prioritySummary['high'] / $prioritySummary['total']) * 100)) : 0; @endphp
                            <div class="bg-orange-400 h-full" style="width: {{ max($highPct, $prioritySummary['high'] > 0 ? 8 : 0) }}%"></div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('officer.reports', ['priority' => 'Urgent']) }}"
                   class="mt-6 py-3 bg-portal-secondary-container text-portal-on-secondary-container font-bold rounded-lg hover:bg-white transition-colors text-center text-sm no-underline block">
                    View Critical Reports
                </a>
            </div>
        </div>
    </div>
</x-officer-admin-layout>

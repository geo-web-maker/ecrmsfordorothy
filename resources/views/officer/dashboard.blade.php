<x-officer-admin-layout active-nav="dashboard" page-title="{{ Auth::user()->isAdmin() ? 'Executive View' : 'My Overview' }}">
    <div class="p-4 sm:p-6 max-w-[1200px] mx-auto w-full space-y-6">
        @include('partials.flash')

        <div>
            <h1 class="text-2xl font-bold text-portal-on-surface m-0">
                {{ Auth::user()->isAdmin() ? 'Executive View' : 'My Overview' }}
            </h1>
            <p class="text-sm text-portal-on-surface-variant mt-1 mb-0">
                {{ Auth::user()->isAdmin() ? 'Platform-wide statistics and recent activity.' : 'Summary of cases assigned to you.' }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @php
                $statCards = [
                    ['label' => 'Total Reports',  'value' => $stats['total'],        'color' => 'text-portal-ink', 'bg' => 'bg-portal-leaf-tint'],
                    ['label' => 'Submitted',      'value' => $stats['submitted'],    'color' => 'text-blue-700', 'bg' => 'bg-blue-50'],
                    ['label' => 'Under Review',   'value' => $stats['under_review'], 'color' => 'text-amber-700', 'bg' => 'bg-amber-50'],
                    ['label' => 'Resolved',       'value' => $stats['resolved'],     'color' => 'text-portal-secondary', 'bg' => 'bg-portal-secondary-container'],
                ];
            @endphp
            @foreach ($statCards as $card)
                <div class="rounded-xl border border-portal-outline-variant bg-white p-4 sm:p-5 shadow-sm">
                    <p class="text-xs font-semibold text-portal-on-surface-variant m-0">{{ $card['label'] }}</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-extrabold m-0 {{ $card['color'] }}">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="{{ route('officer.reports') }}" class="flex items-center gap-4 rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm hover:shadow-md transition-shadow no-underline">
                <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-portal-leaf-tint shrink-0">
                    <span class="material-symbols-outlined text-portal-secondary">list_alt</span>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-portal-ink m-0">{{ Auth::user()->isAdmin() ? 'Incident Queue' : 'My Cases' }}</h3>
                    <p class="text-xs text-portal-on-surface-variant mt-0.5 mb-0">Browse and update reports</p>
                </div>
            </a>
            <a href="{{ route('officer.map') }}" class="flex items-center gap-4 rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm hover:shadow-md transition-shadow no-underline">
                <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-portal-leaf-tint shrink-0">
                    <span class="material-symbols-outlined text-portal-secondary">distance</span>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-portal-ink m-0">Incident Map</h3>
                    <p class="text-xs text-portal-on-surface-variant mt-0.5 mb-0">View reports on a map</p>
                </div>
            </a>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('officer.analytics') }}" class="flex items-center gap-4 rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm hover:shadow-md transition-shadow no-underline">
                    <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-portal-leaf-tint shrink-0">
                        <span class="material-symbols-outlined text-portal-secondary">monitoring</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-portal-ink m-0">Analytics</h3>
                        <p class="text-xs text-portal-on-surface-variant mt-0.5 mb-0">Charts and statistics</p>
                    </div>
                </a>
            @endif
        </div>

        @if ($recent->isNotEmpty())
            <div class="rounded-xl border border-portal-outline-variant bg-white shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-portal-outline-variant bg-portal-surface flex items-center justify-between">
                    <h3 class="text-sm font-bold text-portal-ink m-0">Recent Reports</h3>
                    <a href="{{ route('officer.reports') }}" class="text-xs font-bold text-portal-secondary no-underline">View all</a>
                </div>

                {{-- Mobile: compact cards --}}
                <div class="md:hidden max-h-[min(70vh,28rem)] overflow-y-auto overscroll-y-contain officer-recent-scroll">
                    @foreach ($recent as $report)
                        @include('partials.officer-report-mobile-card', ['report' => $report, 'showLocation' => false])
                    @endforeach
                </div>

                {{-- Desktop: table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left min-w-[500px]">
                        <thead class="bg-portal-surface text-xs font-semibold text-portal-on-surface-variant uppercase">
                            <tr>
                                <th class="px-5 py-3">ID</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-portal-outline-variant text-sm">
                            @foreach ($recent as $report)
                                <tr class="hover:bg-portal-surface transition-colors">
                                    <td class="px-5 py-3 font-bold text-portal-ink">#{{ $report->id }}</td>
                                    <td class="px-5 py-3 font-semibold text-portal-secondary">{{ $report->crimeCategory->name ?? '—' }}</td>
                                    <td class="px-5 py-3">@include('partials.status-badge', ['status' => $report->status])</td>
                                    <td class="px-5 py-3 text-portal-on-surface-variant">{{ $report->created_at->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('officer.report.show', $report) }}" class="text-xs font-bold px-3 py-1.5 rounded-lg text-white bg-portal-secondary no-underline hover:bg-portal-ink transition-colors">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-officer-admin-layout>

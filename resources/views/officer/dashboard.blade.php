<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider" style="color:#5E8B3D;">Admin & Officer Portal</p>
            <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">
                {{ Auth::user()->role === 'admin' ? 'Overview Dashboard' : 'My Assigned Cases' }}
            </h2>
            <p class="mt-1 text-sm" style="color:#5F6B57;">
                {{ Auth::user()->role === 'admin' ? 'Platform-wide statistics and recent activity.' : 'Reports that have been assigned to you for investigation.' }}
            </p>
        </div>
    </x-slot>

    <div class="py-10" style="background:#F3F5EA;">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @php
                    $statCards = [
                        ['label' => 'Total Reports',  'value' => $stats['total'],        'color' => '#1F3318', 'bg' => '#EAF1DD', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1m5 13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6.586a1 1 0 0 1 .707.293l4.414 4.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>'],
                        ['label' => 'Submitted',      'value' => $stats['submitted'],    'color' => '#1d4ed8', 'bg' => '#EFF6FF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>'],
                        ['label' => 'Under Review',   'value' => $stats['under_review'], 'color' => '#b45309', 'bg' => '#FFFBEB', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                        ['label' => 'Resolved',       'value' => $stats['resolved'],     'color' => '#3F6B2A', 'bg' => '#EAF1DD', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>'],
                    ];
                @endphp
                @foreach ($statCards as $card)
                    <div class="rounded-2xl border bg-white p-4 sm:p-5 shadow-sm" style="border-color:rgba(94,139,61,0.12);">
                        <div class="flex items-start gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-xl shrink-0" style="background:{{ $card['bg'] }}">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="{{ $card['color'] }}" stroke-width="2">{!! $card['icon'] !!}</svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold truncate" style="color:#7B8F69;">{{ $card['label'] }}</p>
                                <p class="mt-0.5 text-2xl sm:text-3xl font-extrabold" style="color:{{ $card['color'] }}">{{ $card['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Quick Links --}}
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('officer.reports') }}"
                   class="group flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm transition hover:shadow-md"
                   style="border-color:rgba(94,139,61,0.15); text-decoration:none;">
                    <div class="flex items-center justify-center w-11 h-11 rounded-xl shrink-0" style="background:#EAF1DD;">
                        <svg class="w-5 h-5" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6M9 8h6M9 16h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color:#1F3318;">{{ Auth::user()->role === 'admin' ? 'All Reports' : 'My Reports' }}</h3>
                        <p class="text-xs mt-0.5" style="color:#7B8F69;">Browse and manage submitted cases</p>
                    </div>
                    <svg class="w-4 h-4 ml-auto opacity-40 group-hover:opacity-70 transition" style="color:#3F6B2A;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L11.94 8H6.75a.75.75 0 0 1 0-1.5h6.5a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0V8.81l-4.73 4.73a.75.75 0 0 1-1.06 0z" clip-rule="evenodd"/></svg>
                </a>
                <a href="{{ route('officer.map') }}"
                   class="group flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm transition hover:shadow-md"
                   style="border-color:rgba(94,139,61,0.15); text-decoration:none;">
                    <div class="flex items-center justify-center w-11 h-11 rounded-xl shrink-0" style="background:#EAF1DD;">
                        <svg class="w-5 h-5" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10a8 8 0 1 0-16 0c0 6 8 10 8 10z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color:#1F3318;">Crime Map</h3>
                        <p class="text-xs mt-0.5" style="color:#7B8F69;">View reports on an interactive map</p>
                    </div>
                    <svg class="w-4 h-4 ml-auto opacity-40 group-hover:opacity-70 transition" style="color:#3F6B2A;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L11.94 8H6.75a.75.75 0 0 1 0-1.5h6.5a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0V8.81l-4.73 4.73a.75.75 0 0 1-1.06 0z" clip-rule="evenodd"/></svg>
                </a>
                <a href="{{ route('officer.analytics') }}"
                   class="group flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm transition hover:shadow-md"
                   style="border-color:rgba(94,139,61,0.15); text-decoration:none;">
                    <div class="flex items-center justify-center w-11 h-11 rounded-xl shrink-0" style="background:#EAF1DD;">
                        <svg class="w-5 h-5" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm" style="color:#1F3318;">Analytics</h3>
                        <p class="text-xs mt-0.5" style="color:#7B8F69;">Charts and statistics</p>
                    </div>
                    <svg class="w-4 h-4 ml-auto opacity-40 group-hover:opacity-70 transition" style="color:#3F6B2A;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L11.94 8H6.75a.75.75 0 0 1 0-1.5h6.5a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0V8.81l-4.73 4.73a.75.75 0 0 1-1.06 0z" clip-rule="evenodd"/></svg>
                </a>
            </div>

            {{-- Recent Reports --}}
            @if ($recent->isNotEmpty())
                <div class="mt-8 rounded-2xl border bg-white shadow-sm overflow-hidden" style="border-color:rgba(94,139,61,0.15);">
                    <div class="px-5 py-4 border-b" style="border-color:rgba(94,139,61,0.10); background:#FAFBF7;">
                        <h3 class="text-sm font-bold" style="color:#1F3318;">Recent Reports</h3>
                    </div>
                    {{-- Mobile card list --}}
                    <ul class="divide-y sm:hidden" style="border-color:rgba(94,139,61,0.08);">
                        @foreach ($recent as $report)
                            <li class="p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold" style="color:#1F3318;">#{{ $report->id }} — {{ $report->crimeCategory->name ?? 'Unknown' }}</p>
                                        <p class="text-xs mt-0.5" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</p>
                                        <div class="mt-1.5">@include('partials.status-badge', ['status' => $report->status])</div>
                                    </div>
                                    <a href="{{ route('officer.report.show', $report) }}"
                                       class="shrink-0 text-xs font-bold px-3 py-1.5 rounded-lg text-white transition"
                                       style="background:#3F6B2A; text-decoration:none;">
                                        View
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Desktop table --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y" style="border-color:rgba(94,139,61,0.08);">
                            <thead style="background:#FAFBF7;">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">ID</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Category</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Date</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color:rgba(94,139,61,0.06);">
                                @foreach ($recent as $report)
                                    <tr class="hover:bg-[#F6F8F1] transition">
                                        <td class="px-5 py-3 text-sm font-bold" style="color:#1F3318;">#{{ $report->id }}</td>
                                        <td class="px-5 py-3 text-sm font-semibold" style="color:#3F6B2A;">{{ $report->crimeCategory->name ?? 'Unknown' }}</td>
                                        <td class="px-5 py-3">@include('partials.status-badge', ['status' => $report->status])</td>
                                        <td class="px-5 py-3 text-sm" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="{{ route('officer.report.show', $report) }}"
                                               class="text-xs font-bold px-3 py-1.5 rounded-lg text-white transition"
                                               style="background:#3F6B2A; text-decoration:none;">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

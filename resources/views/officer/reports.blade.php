<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider" style="color:#5E8B3D;">{{ Auth::user()->isAdmin() ? 'Admin Portal' : 'Officer Portal' }}</p>
            <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">
                {{ Auth::user()->isAdmin() ? 'All Reports' : 'My Assigned Reports' }}
            </h2>
            <p class="mt-1 text-sm" style="color:#5F6B57;">
                Browse, filter, and take action on submitted environmental crime reports.
            </p>
        </div>
    </x-slot>

    <div class="py-10" style="background:#F3F5EA;">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            {{-- Status Filters --}}
            <div class="mb-5 flex flex-wrap gap-2" id="status-filters">
                <button type="button" data-filter="all"
                        class="filter-btn rounded-full px-3 py-1 text-xs font-bold transition"
                        style="background:#3F6B2A; color:white;">All</button>
                @foreach (['Submitted', 'Under Review', 'Assigned', 'Resolved', 'Closed'] as $s)
                    <button type="button" data-filter="{{ $s }}"
                            class="filter-btn rounded-full px-3 py-1 text-xs font-bold transition"
                            style="background:#EAF1DD; color:#3F6B2A;">{{ $s }}</button>
                @endforeach
            </div>

            {{-- Mobile: Cards --}}
            <div class="space-y-3 sm:hidden" id="report-cards">
                @foreach ($reports as $report)
                    <div class="report-row rounded-2xl border bg-white p-4 shadow-sm" data-status="{{ $report->status }}"
                         style="border-color:rgba(94,139,61,0.15);">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-extrabold" style="color:#1F3318;">#{{ $report->id }}</p>
                                <p class="text-sm font-semibold mt-0.5 truncate" style="color:#3F6B2A;">{{ $report->crimeCategory->name ?? '—' }}</p>
                                <p class="text-xs mt-0.5" style="color:#7B8F69;">{{ $report->user->name ?? 'Anonymous' }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    @include('partials.status-badge', ['status' => $report->status])
                                    @if ($report->priority)
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                              style="background:#EAF1DD; color:#3F6B2A;">{{ $report->priority }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-xs" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</p>
                                <a href="{{ route('officer.report.show', $report) }}"
                                   class="mt-2 inline-block text-xs font-bold px-3 py-1.5 rounded-lg text-white"
                                   style="background:#3F6B2A; text-decoration:none;">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop: Table --}}
            <div class="hidden sm:block overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color:rgba(94,139,61,0.08);" id="reports-table">
                        <thead style="background:#FAFBF7;">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">ID</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Reporter</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Category</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Priority</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57;">Date</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:rgba(94,139,61,0.06);">
                            @foreach ($reports as $report)
                                <tr class="report-row hover:bg-[#F6F8F1] transition" data-status="{{ $report->status }}">
                                    <td class="px-5 py-3 text-sm font-bold" style="color:#1F3318;">#{{ $report->id }}</td>
                                    <td class="px-5 py-3 text-sm" style="color:#3A4233;">{{ $report->user->name ?? 'Anonymous' }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold" style="color:#3F6B2A;">{{ $report->crimeCategory->name ?? '—' }}</td>
                                    <td class="px-5 py-3">@include('partials.status-badge', ['status' => $report->status])</td>
                                    <td class="px-5 py-3 text-sm font-semibold" style="color:#3A4233;">{{ $report->priority ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('officer.report.show', $report) }}"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg text-white transition"
                                           style="background:#3F6B2A; text-decoration:none;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const filter = btn.dataset.filter;
                    // Update button styles
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.style.background = b === btn ? '#3F6B2A' : '#EAF1DD';
                        b.style.color = b === btn ? 'white' : '#3F6B2A';
                    });
                    // Filter table rows
                    document.querySelectorAll('#reports-table .report-row').forEach(row => {
                        row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
                    });
                    // Filter mobile cards
                    document.querySelectorAll('#report-cards .report-row').forEach(card => {
                        card.style.display = (filter === 'all' || card.dataset.status === filter) ? '' : 'none';
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

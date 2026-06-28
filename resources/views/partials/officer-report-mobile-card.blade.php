@php
    $priorityStyles = $priorityStyles ?? [
        'Urgent'   => 'bg-red-100 text-red-800',
        'Critical' => 'bg-red-100 text-red-800',
        'High'     => 'bg-portal-secondary-container text-portal-on-secondary-container',
        'Medium'   => 'bg-gray-200 text-gray-700',
        'Low'      => 'bg-gray-100 text-gray-600',
    ];
    $statusDots = $statusDots ?? [
        'Submitted'     => 'bg-yellow-500',
        'Under Review'  => 'bg-green-500',
        'Assigned'      => 'bg-blue-500',
        'Resolved'      => 'bg-emerald-600',
        'Closed'        => 'bg-gray-400',
    ];
    $resolveIcon = $resolveIcon ?? function (?string $name) {
        $lower = strtolower($name ?? '');
        foreach (['logging' => 'forest', 'wetland' => 'water_drop', 'pollution' => 'water_drop', 'encroach' => 'fence', 'poach' => 'pets'] as $key => $icon) {
            if (str_contains($lower, $key)) {
                return $icon;
            }
        }
        return 'eco';
    };
    $categoryName = $report->crimeCategory->name ?? '—';
    $icon = is_callable($resolveIcon) ? $resolveIcon($categoryName) : 'eco';
@endphp

<article class="officer-report-mobile-card border-b border-portal-outline-variant last:border-b-0 bg-white">
    <div class="p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-portal-leaf-tint">
                <span class="material-symbols-outlined text-portal-secondary text-xl">{{ $icon }}</span>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-bold text-portal-ink m-0">#{{ $report->id }}</p>
                    @if ($report->priority)
                        <span class="shrink-0 px-2 py-0.5 text-[10px] font-bold rounded-full uppercase {{ $priorityStyles[$report->priority] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $report->priority === 'Urgent' ? 'Critical' : $report->priority }}
                        </span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-portal-secondary mt-0.5 m-0 truncate">{{ $categoryName }}</p>
            </div>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
            <div class="min-w-0">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-portal-on-surface-variant m-0">Status</p>
                <p class="flex items-center gap-1.5 font-semibold text-portal-ink mt-0.5 m-0 truncate">
                    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $statusDots[$report->status] ?? 'bg-gray-400' }}"></span>
                    {{ $report->status }}
                </p>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-portal-on-surface-variant m-0">Submitted</p>
                <p class="font-medium text-portal-on-surface-variant mt-0.5 m-0">{{ $report->created_at->format('M d, Y') }}</p>
            </div>
            @if (!empty($showLocation))
                @php $locationLabel = $report->location_address ?? $report->crime->location ?? null; @endphp
                @if ($locationLabel)
                <div class="col-span-2 min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-portal-on-surface-variant m-0">Location</p>
                    <p class="font-medium text-portal-ink mt-0.5 m-0 truncate">{{ $locationLabel }}</p>
                </div>
                @endif
            @endif
        </div>

        <a href="{{ route('officer.report.show', $report) }}"
           class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-sm font-bold text-white bg-portal-secondary hover:bg-portal-ink transition-colors no-underline touch-manipulation">
            <span class="material-symbols-outlined text-base">visibility</span>
            View case
        </a>
    </div>
</article>

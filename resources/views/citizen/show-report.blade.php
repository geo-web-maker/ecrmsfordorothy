@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider" style="color:#5E8B3D;">Citizen Portal</p>
                <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">Report #{{ $report->id }}</h2>
            </div>
            <a href="{{ route('citizen.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm font-bold self-start sm:self-auto"
               style="color:#3F6B2A; text-decoration:none;">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1 0 1.06L9.06 10l3.73 3.71a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0z" clip-rule="evenodd"/>
                </svg>
                My Reports
            </a>
        </div>
    </x-slot>

    <div class="py-8" style="background:#F3F5EA;">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-5">
            @include('partials.flash')

            {{-- Summary Card --}}
            <div class="rounded-2xl border bg-white p-5 sm:p-6 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @include('partials.status-badge', ['status' => $report->status])
                    @if($report->priority)
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold"
                              style="background:#EAF1DD; color:#3F6B2A; border:1px solid #D9E8C5;">
                            {{ $report->priority }} priority
                        </span>
                    @endif
                    @if ($report->tracking_code)
                        <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-full"
                              style="background:#F3F5EA; color:#5F6B57;">
                            Code: {{ $report->tracking_code }}
                        </span>
                    @endif
                </div>
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Category</dt>
                        <dd class="mt-1 font-semibold" style="color:#3F6B2A;">{{ $report->crimeCategory->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Submitted</dt>
                        <dd class="mt-1 font-semibold" style="color:#1F3318;">{{ $report->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if ($report->location_address)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Location</dt>
                            <dd class="mt-1 font-semibold" style="color:#1F3318;">{{ $report->location_address }}</dd>
                        </div>
                    @endif
                    @if ($report->mapLatitude() && $report->mapLongitude())
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Coordinates</dt>
                            <dd class="mt-1 font-mono text-sm font-semibold" style="color:#1F3318;">{{ $report->mapLatitude() }}, {{ $report->mapLongitude() }}</dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#7B8F69;">Description</dt>
                        <dd class="rounded-xl border p-4 text-sm leading-relaxed whitespace-pre-wrap"
                            style="color:#1F3318; background:#FAFBF7; border-color:rgba(94,139,61,0.12);">{{ $report->description }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Evidence --}}
            @if ($report->evidence->isNotEmpty())
                <div class="rounded-2xl border bg-white p-5 sm:p-6 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4" style="color:#3F6B2A;">Evidence</h3>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($report->evidence as $item)
                            <div class="rounded-xl border overflow-hidden" style="border-color:rgba(94,139,61,0.12);">
                                @if ($item->file_type === 'image')
                                    <x-lazy-image
                                        :src="Storage::url($item->file_path)"
                                        alt="Evidence"
                                        class="h-28 sm:h-36 w-full object-cover"
                                        height="9rem"
                                    />
                                @else
                                    <div class="lazy-media" data-lazy-media style="min-height: 9rem;">
                                        <div class="lazy-media__skeleton skeleton skeleton-shimmer" aria-hidden="true"></div>
                                        <video src="{{ Storage::url($item->file_path) }}" controls preload="none" class="lazy-media__video h-28 sm:h-36 w-full object-cover"></video>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Status History (no remarks shown to citizen) --}}
            @if ($report->statusHistory->isNotEmpty())
                <div class="rounded-2xl border bg-white p-5 sm:p-6 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-4" style="color:#3F6B2A;">Status History</h3>
                    <ol class="relative border-l ml-2.5 space-y-5" style="border-color:rgba(94,139,61,0.2);">
                        @foreach ($report->statusHistory as $entry)
                            <li class="ml-5">
                                <span class="absolute flex items-center justify-center w-5 h-5 rounded-full -left-2.5 border-2"
                                      style="background:#EAF1DD; border-color:#3F6B2A;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:#3F6B2A;"></span>
                                </span>
                                <p class="text-sm font-bold" style="color:#1F3318;">
                                    {{ $entry->old_status }} &rarr; {{ $entry->new_status }}
                                </p>
                                <time class="text-xs font-semibold" style="color:#7B8F69;">
                                    {{ $entry->changed_at?->format('M d, Y H:i') ?? '—' }}
                                </time>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider" style="color:#5E8B3D;">Citizen Portal</p>
                <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">My Reports</h2>
                <p class="mt-1 text-sm" style="color:#5F6B57;">Track the progress and details of environmental crime reports you've submitted.</p>
            </div>
            <div>
                <a href="{{ route('citizen.report.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg focus:outline-none cursor-pointer"
                   style="background:#3F6B2A; text-decoration:none;"
                   onmouseover="this.style.background='#2C4424';"
                   onmouseout="this.style.background='#3F6B2A';">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H4a1 1 0 1 1 0-2h5V4a1 1 0 0 1 1-1z" />
                    </svg>
                    New Report
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10" style="background:#F3F5EA;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            @if ($reports->isEmpty())
                <div class="rounded-2xl border bg-white p-16 text-center shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl" style="background:#EAF1DD;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1m5 13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6.586a1 1 0 0 1 .707.293l4.414 4.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-lg font-bold" style="color:#1F3318;">No reports submitted yet</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm font-medium" style="color:#7B8F69; line-height:1.6;">
                        When you submit an environmental crime report, you'll be able to track its progress and official action status directly from this dashboard.
                    </p>
                    <a href="{{ route('citizen.report.create') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg cursor-pointer"
                       style="background:#3F6B2A; text-decoration:none;"
                       onmouseover="this.style.background='#2C4424';"
                       onmouseout="this.style.background='#3F6B2A';">
                        Submit your first report
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L11.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 1 1-1.04-1.08l3.158-2.96H3.75A.75.75 0 0 1 3 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            @else
                {{-- Mobile: vertical card list --}}
                <div class="md:hidden flex flex-col gap-4">
                    @foreach ($reports as $report)
                        @php
                            $priorityStyles = match (strtolower($report->priority ?? '')) {
                                'critical' => 'background:#FDF2F2; color:#9B1C1C; border:1px solid #FBD5D5;',
                                'high' => 'background:#FFF8F1; color:#B45309; border:1px solid #FEE2E2;',
                                'medium' => 'background:#FFFBEB; color:#B45309; border:1px solid #FEF3C7;',
                                default => 'background:#EAF1DD; color:#3F6B2A; border:1px solid #D9E8C5;',
                            };
                        @endphp
                        <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider m-0" style="color:#7B8F69;">Report</p>
                                    <h3 class="text-lg font-bold m-0 mt-0.5" style="color:#1F3318;">#{{ $report->id }}</h3>
                                </div>
                                @include('partials.status-badge', ['status' => $report->status])
                            </div>

                            <dl class="grid grid-cols-1 gap-3 text-sm mb-4">
                                <div>
                                    <dt class="text-[10px] font-bold uppercase tracking-wider" style="color:#7B8F69;">Category</dt>
                                    <dd class="font-semibold mt-0.5 m-0" style="color:#3F6B2A;">{{ $report->crimeCategory->name ?? '—' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <dt class="text-[10px] font-bold uppercase tracking-wider" style="color:#7B8F69;">Priority</dt>
                                        <dd class="mt-1 m-0">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold capitalize" style="{{ $priorityStyles }}">
                                                {{ $report->priority }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="text-right">
                                        <dt class="text-[10px] font-bold uppercase tracking-wider" style="color:#7B8F69;">Submitted</dt>
                                        <dd class="font-medium mt-0.5 m-0" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</dd>
                                    </div>
                                </div>
                            </dl>

                            <button type="button"
                                    onclick="openReportModal({{ $report->id }})"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all shadow-sm cursor-pointer border-none"
                                    style="background:#3F6B2A;">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L11.94 8H6.75a.75.75 0 0 1 0-1.5h6.5a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0V8.81l-4.73 4.73a.75.75 0 0 1-1.06 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </article>
                    @endforeach
                </div>

                {{-- Desktop: table --}}
                <div class="hidden md:block overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#EDF0E5]">
                            <thead style="background:#FAFBF7;">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">Category</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">Priority</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">Submitted</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="color:#5F6B57; border-bottom: 1px solid rgba(94,139,61,0.1);">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EDF0E5]">
                                @foreach ($reports as $report)
                                    @php
                                        $priorityStyles = match (strtolower($report->priority ?? '')) {
                                            'critical' => 'background:#FDF2F2; color:#9B1C1C; border:1px solid #FBD5D5;',
                                            'high' => 'background:#FFF8F1; color:#B45309; border:1px solid #FEE2E2;',
                                            'medium' => 'background:#FFFBEB; color:#B45309; border:1px solid #FEF3C7;',
                                            default => 'background:#EAF1DD; color:#3F6B2A; border:1px solid #D9E8C5;',
                                        };
                                    @endphp
                                    <tr class="transition hover:bg-[#F6F8F1]">
                                        <td class="px-6 py-4 text-sm font-bold" style="color:#1F3318;">#{{ $report->id }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold" style="color:#3F6B2A;">{{ $report->crimeCategory->name ?? '—' }}</td>
                                        <td class="px-6 py-4">@include('partials.status-badge', ['status' => $report->status])</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold capitalize" style="{{ $priorityStyles }}">
                                                {{ $report->priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium" style="color:#7B8F69;">{{ $report->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button"
                                                    onclick="openReportModal({{ $report->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white transition-all shadow-sm cursor-pointer border-none"
                                                    style="background:#3F6B2A;"
                                                    onmouseover="this.style.background='#2C4424'; this.style.transform='translateY(-1px)';"
                                                    onmouseout="this.style.background='#3F6B2A'; this.style.transform='translateY(0)';">
                                                View Details
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L11.94 8H6.75a.75.75 0 0 1 0-1.5h6.5a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0V8.81l-4.73 4.73a.75.75 0 0 1-1.06 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
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

    @if (!$reports->isEmpty())
        @foreach ($reports as $report)
            @php
                $priorityStyles = match (strtolower($report->priority ?? '')) {
                    'critical' => 'background:#FDF2F2; color:#9B1C1C; border:1px solid #FBD5D5;',
                    'high' => 'background:#FFF8F1; color:#B45309; border:1px solid #FEE2E2;',
                    'medium' => 'background:#FFFBEB; color:#B45309; border:1px solid #FEF3C7;',
                    default => 'background:#EAF1DD; color:#3F6B2A; border:1px solid #D9E8C5;',
                };
            @endphp
            <!-- Modal for Report #{{ $report->id }} -->
            <div id="report-modal-{{ $report->id }}" 
                 class="report-modal-backdrop hidden-state fixed inset-0 z-[15000] flex items-center justify-center p-4 pt-20 sm:pt-4 bg-black/60 backdrop-blur-sm"
                 onclick="closeReportModal({{ $report->id }})">
                
                <div class="report-modal-content hidden-state bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border"
                     style="border-color:rgba(94,139,61,0.15);"
                     onclick="event.stopPropagation()">
                     
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b px-6 py-4" style="border-color:rgba(94,139,61,0.1);">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold" style="color:#1F3318;">Report #{{ $report->id }}</h3>
                            @include('partials.status-badge', ['status' => $report->status])
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold capitalize" style="{{ $priorityStyles }}">
                                {{ $report->priority }} priority
                            </span>
                        </div>
                        <button type="button" 
                                onclick="closeReportModal({{ $report->id }})" 
                                class="text-[#6B7568] hover:text-[#1F3318] hover:bg-[#F6F8F1] p-1.5 rounded-xl transition-colors border-none bg-transparent cursor-pointer"
                                title="Close details">
                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Scrollable Body -->
                    <div class="overflow-y-auto p-6 space-y-6 flex-1 text-left">
                        <!-- Basic Details -->
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:#3F6B2A;">Incident details</h4>
                            <dl class="grid gap-x-4 gap-y-3 sm:grid-cols-2 text-sm">
                                <div>
                                    <dt class="text-[#5F6B57] font-semibold text-xs uppercase tracking-wider">Category</dt>
                                    <dd class="text-[#1F3318] font-bold mt-1">{{ $report->crimeCategory->name ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[#5F6B57] font-semibold text-xs uppercase tracking-wider">Submitted</dt>
                                    <dd class="text-[#1F3318] font-semibold mt-1">{{ $report->created_at->format('M d, Y \a\t H:i') }}</dd>
                                </div>
                                @if ($report->tracking_code)
                                <div>
                                    <dt class="text-[#5F6B57] font-semibold text-xs uppercase tracking-wider">Tracking Code</dt>
                                    <dd class="text-[#3F6B2A] font-mono font-bold select-all mt-1 bg-[#EAF1DD] px-2 py-1 rounded-lg inline-block">{{ $report->tracking_code }}</dd>
                                </div>
                                @endif
                                @if ($report->location_address)
                                <div class="sm:col-span-2">
                                    <dt class="text-[#5F6B57] font-semibold text-xs uppercase tracking-wider">Location Address</dt>
                                    <dd class="text-[#1F3318] font-semibold mt-1">{{ $report->location_address }}</dd>
                                </div>
                                @endif
                                @if ($report->location_latitude && $report->location_longitude)
                                <div class="sm:col-span-2">
                                    <dt class="text-[#5F6B57] font-semibold text-xs uppercase tracking-wider">Coordinates</dt>
                                    <dd class="text-[#1F3318] font-mono font-semibold mt-1">{{ $report->location_latitude }}, {{ $report->location_longitude }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        
                        <!-- Description -->
                        <div class="border-t pt-5" style="border-color:rgba(94,139,61,0.1);">
                            <h4 class="text-xs font-bold uppercase tracking-wider mb-2.5" style="color:#3F6B2A;">Description</h4>
                            <div class="rounded-2xl p-4 text-[#1F3318] text-sm leading-relaxed whitespace-pre-wrap border bg-[#FAFBF7]" style="border-color:rgba(94,139,61,0.12);">{{ $report->description }}</div>
                        </div>
                        
                        <!-- Evidence Section -->
                        @if ($report->evidence->isNotEmpty())
                        <div class="border-t pt-5" style="border-color:rgba(94,139,61,0.1);">
                            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:#3F6B2A;">Evidence files</h4>
                            <div class="grid gap-3 grid-cols-2 sm:grid-cols-3">
                                @foreach ($report->evidence as $item)
                                <div class="rounded-xl border overflow-hidden bg-[#FAFBF7]" style="border-color:rgba(94,139,61,0.12);">
                                    @if ($item->file_type === 'image')
                                        <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" title="View Full Image">
                                            <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" title="View Full Image">
                                                <x-lazy-image
                                                    :src="asset('storage/'.$item->file_path)"
                                                    alt="Evidence"
                                                    class="h-28 w-full object-cover hover:scale-105 transition duration-300"
                                                    height="7rem"
                                                />
                                            </a>
                                    @else
                                        <div class="lazy-media" data-lazy-media style="min-height: 7rem;">
                                            <div class="lazy-media__skeleton skeleton skeleton-shimmer" aria-hidden="true"></div>
                                            <video src="{{ asset('storage/'.$item->file_path) }}" controls preload="none" class="lazy-media__video h-28 w-full object-cover"></video>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Status History Timeline -->
                        @if ($report->statusHistory->isNotEmpty())
                        <div class="border-t pt-5" style="border-color:rgba(94,139,61,0.1);">
                            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:#3F6B2A;">Status history</h4>
                            <ol class="relative border-l ml-2.5 space-y-5" style="border-color:rgba(94,139,61,0.2);">
                                @foreach ($report->statusHistory as $entry)
                                <li class="ml-6">
                                    <span class="absolute flex items-center justify-center w-5 h-5 rounded-full -left-2.5 bg-[#EAF1DD] border-2" style="border-color:#3F6B2A;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background:#3F6B2A;"></span>
                                    </span>
                                    <div class="text-sm font-bold" style="color:#1F3318;">
                                        {{ $entry->old_status }} &rarr; {{ $entry->new_status }}
                                    </div>
                                    <time class="block mb-1 text-[11px] font-bold" style="color:#7B8F69;">
                                        {{ $entry->changed_at?->format('M d, Y \a\t H:i') ?? '—' }}
                                    </time>
                                </li>
                                @endforeach
                            </ol>
                        </div>
                        @endif
                    </div>
                     
                    <!-- Footer -->
                    <div class="border-t px-6 py-4 bg-[#FAFBF7] flex justify-end gap-3 rounded-b-2xl" style="border-color:rgba(94,139,61,0.1);">
                        <button type="button" 
                                onclick="closeReportModal({{ $report->id }})" 
                                class="rounded-xl border-2 px-5 py-2.5 text-sm font-bold text-[#5F6B57] bg-white transition cursor-pointer"
                                style="border-color:#D8DECB;"
                                onmouseover="this.style.background='#F6F8F1'; this.style.color='#3F6B2A'; this.style.borderColor='#3F6B2A/40';"
                                onmouseout="this.style.background='white'; this.style.color='#5F6B57'; this.style.borderColor='#D8DECB';">
                            Close details
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @push('styles')
    <style>
        .report-modal-backdrop {
            transition: opacity 0.25s ease-out;
        }
        .report-modal-content {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease-out;
        }
        .report-modal-backdrop.hidden-state {
            opacity: 0;
            pointer-events: none;
        }
        .report-modal-backdrop.show-state {
            opacity: 1;
            pointer-events: auto;
        }
        .report-modal-content.hidden-state {
            transform: scale(0.95);
            opacity: 0;
        }
        .report-modal-content.show-state {
            transform: scale(1);
            opacity: 1;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openReportModal(id) {
            const backdrop = document.getElementById(`report-modal-${id}`);
            const content = backdrop.querySelector('.report-modal-content');
            
            backdrop.classList.remove('hidden-state');
            backdrop.classList.add('show-state');
            
            setTimeout(() => {
                content.classList.remove('hidden-state');
                content.classList.add('show-state');
            }, 20);
            
            document.body.classList.add('overflow-hidden');
        }
        
        function closeReportModal(id) {
            const backdrop = document.getElementById(`report-modal-${id}`);
            const content = backdrop.querySelector('.report-modal-content');
            
            content.classList.remove('show-state');
            content.classList.add('hidden-state');
            
            setTimeout(() => {
                backdrop.classList.remove('show-state');
                backdrop.classList.add('hidden-state');
                document.body.classList.remove('overflow-hidden');
            }, 250);
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openBackdrop = document.querySelector('.report-modal-backdrop.show-state');
                if (openBackdrop) {
                    const id = openBackdrop.id.replace('report-modal-', '');
                    closeReportModal(id);
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
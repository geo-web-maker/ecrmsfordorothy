@php use Illuminate\Support\Facades\Storage; @endphp
<x-officer-admin-layout active-nav="reports" page-title="Report #{{ $report->id }}">
    <div class="p-4 sm:p-6 max-w-6xl mx-auto w-full">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-portal-secondary m-0">Case Details</p>
                <h1 class="text-2xl font-bold text-portal-ink mt-1 mb-0">Report #{{ $report->id }}</h1>
            </div>
            <a href="{{ route('officer.reports') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-portal-secondary no-underline self-start">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Back to queue
            </a>
        </div>

        @include('partials.flash')

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-5">
                <div class="rounded-xl border border-portal-outline-variant bg-white p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-wrap gap-2 mb-4">
                        @include('partials.status-badge', ['status' => $report->status])
                        @if ($report->priority)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-portal-secondary-container text-portal-on-secondary-container">
                                {{ $report->priority }} Priority
                            </span>
                        @endif
                    </div>

                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant">Reporter</dt>
                            <dd class="mt-1 font-semibold text-portal-ink">{{ $report->user->name ?? 'Anonymous' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant">Category</dt>
                            <dd class="mt-1 font-semibold text-portal-secondary">{{ $report->crimeCategory->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant">Tracking Code</dt>
                            <dd class="mt-1 font-mono font-semibold text-portal-ink">{{ $report->tracking_code ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant">Submitted</dt>
                            <dd class="mt-1 font-semibold text-portal-ink">{{ $report->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @if ($report->location_address ?? $report->crime?->location)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant">Location</dt>
                                <dd class="mt-1 font-semibold text-portal-ink">{{ $report->location_address ?? $report->crime->location }}</dd>
                            </div>
                        @endif
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-wider text-portal-on-surface-variant mb-2">Description</dt>
                            <dd class="rounded-xl border border-portal-outline-variant p-4 text-sm leading-relaxed whitespace-pre-wrap bg-portal-surface text-portal-ink m-0">{{ $report->description }}</dd>
                        </div>
                    </dl>
                </div>

                @if ($report->evidence->isNotEmpty())
                    <div class="rounded-xl border border-portal-outline-variant bg-white p-5 sm:p-6 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-portal-secondary mb-4 m-0">Evidence</h3>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($report->evidence as $item)
                                <div class="rounded-xl border border-portal-outline-variant overflow-hidden">
                                    @if ($item->file_type === 'image')
                                        <a href="{{ Storage::url($item->file_path) }}" target="_blank">
                                            <x-lazy-image
                                                :src="Storage::url($item->file_path)"
                                                alt="Evidence"
                                                class="h-28 sm:h-36 w-full object-cover hover:scale-105 transition duration-300"
                                                height="9rem"
                                            />
                                        </a>
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
            </div>

            <div class="space-y-5">
                <div class="rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-bold text-portal-ink mb-3 m-0">Update Status &amp; Feedback</h3>
                    <form method="POST" action="{{ route('officer.report.status', $report) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-portal-secondary mb-1.5">Status</label>
                            <select name="status" required class="block w-full rounded-lg border-portal-outline-variant text-sm font-semibold focus:ring-portal-secondary focus:border-portal-secondary">
                                @foreach (['Submitted', 'Under Review', 'Assigned', 'Resolved', 'Closed'] as $s)
                                    <option value="{{ $s }}" @selected($report->status === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-portal-secondary mb-1.5">Feedback / Remarks</label>
                            <textarea name="remarks" rows="4" placeholder="Add investigation notes or feedback for the case record…"
                                      class="block w-full rounded-lg border-portal-outline-variant text-sm focus:ring-portal-secondary focus:border-portal-secondary resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-lg px-4 py-2.5 text-sm font-bold text-white bg-portal-secondary hover:bg-portal-ink transition-colors border-none cursor-pointer">
                            Save Update
                        </button>
                    </form>
                </div>

                @if (Auth::user()->isAdmin())
                    <div class="rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-bold text-portal-ink mb-3 m-0">Assign Officer</h3>
                        <form method="POST" action="{{ route('officer.report.assign', $report) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-portal-secondary mb-1.5">Officer</label>
                                <select name="stuff_id" required class="block w-full rounded-lg border-portal-outline-variant text-sm font-semibold focus:ring-portal-secondary focus:border-portal-secondary">
                                    <option value="">Select officer</option>
                                    @foreach ($officers as $officer)
                                        <option value="{{ $officer->stuff_id }}" @selected($report->caseAssignments->first()?->stuff_id === $officer->stuff_id)>{{ $officer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-portal-secondary mb-1.5">Priority</label>
                                <select name="priority" required class="block w-full rounded-lg border-portal-outline-variant text-sm font-semibold focus:ring-portal-secondary focus:border-portal-secondary">
                                    @foreach (['Low', 'Medium', 'High', 'Urgent'] as $p)
                                        <option value="{{ $p }}" @selected($report->priority === $p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-portal-secondary mb-1.5">Assignment Notes</label>
                                <textarea name="notes" rows="3" placeholder="Assignment instructions…"
                                          class="block w-full rounded-lg border-portal-outline-variant text-sm focus:ring-portal-secondary resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-lg px-4 py-2.5 text-sm font-bold text-white bg-portal-ink hover:opacity-90 transition-opacity border-none cursor-pointer">
                                Assign Officer
                            </button>
                        </form>
                    </div>
                @endif

                @if ($report->statusHistory->isNotEmpty())
                    <div class="rounded-xl border border-portal-outline-variant bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-bold text-portal-ink mb-4 m-0">Status History</h3>
                        <ol class="relative border-l border-portal-outline-variant ml-2.5 space-y-5">
                            @foreach ($report->statusHistory as $entry)
                                <li class="ml-5">
                                    <span class="absolute flex items-center justify-center w-5 h-5 rounded-full -left-2.5 border-2 bg-portal-leaf-tint border-portal-secondary">
                                        <span class="w-1.5 h-1.5 rounded-full bg-portal-secondary"></span>
                                    </span>
                                    <p class="text-xs font-bold text-portal-ink m-0">{{ $entry->old_status }} → {{ $entry->new_status }}</p>
                                    <time class="text-[11px] font-semibold text-portal-on-surface-variant">{{ $entry->changed_at?->diffForHumans() ?? '—' }}</time>
                                    @if ($entry->remarks)
                                        <div class="mt-2 rounded-xl border border-portal-outline-variant p-3 text-xs leading-relaxed bg-portal-surface">
                                            @if ($entry->changedBy)
                                                <span class="inline-block px-2 py-0.5 rounded-full text-white text-[10px] font-bold mb-1.5 {{ $entry->changedBy->isAdmin() ? 'bg-indigo-600' : 'bg-portal-secondary' }}">
                                                    {{ $entry->changedBy->isAdmin() ? 'Admin' : 'Officer' }}
                                                </span>
                                            @endif
                                            <p class="m-0 font-medium whitespace-pre-wrap text-portal-ink">{{ $entry->remarks }}</p>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-officer-admin-layout>

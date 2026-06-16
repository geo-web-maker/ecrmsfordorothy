<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider" style="color:#5E8B3D;">Report Details</p>
                <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">Report #{{ $report->id }}</h2>
            </div>
            <a href="{{ route('officer.reports') }}"
               class="inline-flex items-center gap-1.5 text-sm font-bold self-start sm:self-auto"
               style="color:#3F6B2A; text-decoration:none;">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1 0 1.06L9.06 10l3.73 3.71a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0z" clip-rule="evenodd"/>
                </svg>
                All Reports
            </a>
        </div>
    </x-slot>

    <div class="py-8" style="background:#F3F5EA;">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            {{-- Main layout: stacks on mobile, side-by-side on large screens --}}
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- LEFT: Report Details --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Report Summary Card --}}
                    <div class="rounded-2xl border bg-white p-5 sm:p-6 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                        <div class="flex flex-wrap gap-2 mb-4">
                            @include('partials.status-badge', ['status' => $report->status])
                            @if($report->priority)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold"
                                  style="background:#EAF1DD; color:#3F6B2A; border:1px solid #D9E8C5;">
                                {{ $report->priority }} Priority
                            </span>
                            @endif
                        </div>

                        <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Reporter</dt>
                                <dd class="mt-1 font-semibold" style="color:#1F3318;">{{ $report->user->name ?? 'Anonymous' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Category</dt>
                                <dd class="mt-1 font-semibold" style="color:#3F6B2A;">{{ $report->crimeCategory->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Tracking Code</dt>
                                <dd class="mt-1 font-mono font-semibold" style="color:#1F3318;">{{ $report->tracking_code ?? '—' }}</dd>
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
                            @if ($report->location_latitude)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-bold uppercase tracking-wider" style="color:#7B8F69;">Coordinates</dt>
                                <dd class="mt-1 font-mono text-sm break-all" style="color:#3F6B2A;">{{ $report->location_latitude }}, {{ $report->location_longitude }}</dd>
                            </div>
                            @endif
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#7B8F69;">Description</dt>
                                <dd class="rounded-xl border p-4 text-sm leading-relaxed whitespace-pre-wrap" style="color:#1F3318; background:#FAFBF7; border-color:rgba(94,139,61,0.12);">{{ $report->description }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Evidence --}}
                    @if ($report->evidence->isNotEmpty())
                        <div class="rounded-2xl border bg-white p-5 sm:p-6 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                            <h3 class="text-xs font-bold uppercase tracking-wider mb-4" style="color:#3F6B2A;">Evidence</h3>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach ($report->evidence as $item)
                                    <div class="rounded-xl border overflow-hidden" style="border-color:rgba(94,139,61,0.12);">
                                        @if ($item->file_type === 'image')
                                            <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$item->file_path) }}" alt="Evidence"
                                                     class="h-28 sm:h-36 w-full object-cover hover:scale-105 transition duration-300">
                                            </a>
                                        @else
                                            <video src="{{ asset('storage/'.$item->file_path) }}" controls
                                                   class="h-28 sm:h-36 w-full object-cover"></video>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- RIGHT: Action Sidebar --}}
                <div class="space-y-5">

                    {{-- Update Status --}}
                    <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                        <h3 class="text-sm font-bold mb-3" style="color:#1F3318;">Update Status</h3>
                        <form method="POST" action="{{ route('officer.report.status', $report) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color:#3F6B2A;">Status</label>
                                <select name="status" required
                                        class="block w-full rounded-xl border-2 px-3 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2"
                                        style="border-color:#D8DECB; color:#1F3318; background:white;">
                                    @foreach (['Submitted', 'Under Review', 'Assigned', 'Resolved', 'Closed'] as $s)
                                        <option value="{{ $s }}" @selected($report->status === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color:#3F6B2A;">Remarks</label>
                                <textarea name="remarks" rows="3" placeholder="Add update notes…"
                                          class="block w-full rounded-xl border-2 px-3 py-2 text-sm transition focus:outline-none focus:ring-2 resize-none"
                                          style="border-color:#D8DECB; color:#1F3318; background:white;"></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full rounded-xl px-4 py-2.5 text-sm font-bold text-white transition cursor-pointer"
                                    style="background:#3F6B2A; border:none;"
                                    onmouseover="this.style.background='#2C4424';"
                                    onmouseout="this.style.background='#3F6B2A';">
                                Update Status
                            </button>
                        </form>
                    </div>

                    {{-- Assign Officer (Admin only) --}}
                    @if (Auth::user()->role === 'admin')
                    <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                        <h3 class="text-sm font-bold mb-3" style="color:#1F3318;">Assign Officer</h3>
                        <form method="POST" action="{{ route('officer.report.assign', $report) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color:#3F6B2A;">Officer</label>
                                <select name="officer_id" required
                                        class="block w-full rounded-xl border-2 px-3 py-2 text-sm font-semibold focus:outline-none"
                                        style="border-color:#D8DECB; color:#1F3318; background:white;">
                                    <option value="">Select officer</option>
                                    @foreach ($officers as $officer)
                                        <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color:#3F6B2A;">Priority</label>
                                <select name="priority" required
                                        class="block w-full rounded-xl border-2 px-3 py-2 text-sm font-semibold focus:outline-none"
                                        style="border-color:#D8DECB; color:#1F3318; background:white;">
                                    <option value="">Select priority</option>
                                    <option value="Low"      @selected($report->priority === 'Low')>Low</option>
                                    <option value="Medium"   @selected($report->priority === 'Medium')>Medium</option>
                                    <option value="High"     @selected($report->priority === 'High')>High</option>
                                    <option value="Critical" @selected($report->priority === 'Critical')>Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color:#3F6B2A;">Assignment Notes</label>
                                <textarea name="notes" rows="3" placeholder="Assignment instructions…"
                                          class="block w-full rounded-xl border-2 px-3 py-2 text-sm focus:outline-none resize-none"
                                          style="border-color:#D8DECB; color:#1F3318; background:white;"></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full rounded-xl px-4 py-2.5 text-sm font-bold text-white transition cursor-pointer"
                                    style="background:#1F3318; border:none;"
                                    onmouseover="this.style.background='#2C4424';"
                                    onmouseout="this.style.background='#1F3318';">
                                Assign Officer
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- Status History --}}
                    @if ($report->statusHistory->isNotEmpty())
                        <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:rgba(94,139,61,0.15);">
                            <h3 class="text-sm font-bold mb-4" style="color:#1F3318;">Status History</h3>
                            <ol class="relative border-l ml-2.5 space-y-5" style="border-color:rgba(94,139,61,0.2);">
                                @foreach ($report->statusHistory as $entry)
                                    <li class="ml-5">
                                        <span class="absolute flex items-center justify-center w-5 h-5 rounded-full -left-2.5 border-2"
                                              style="background:#EAF1DD; border-color:#3F6B2A;">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background:#3F6B2A;"></span>
                                        </span>
                                        <p class="text-xs font-bold" style="color:#1F3318;">
                                            {{ $entry->old_status }} &rarr; {{ $entry->new_status }}
                                        </p>
                                        <time class="text-[11px] font-semibold" style="color:#7B8F69;">
                                            {{ $entry->created_at->diffForHumans() }}
                                        </time>
                                        @if ($entry->remarks)
                                            <div class="mt-2 rounded-xl border p-3 text-xs leading-relaxed"
                                                 style="background:#FAFBF7; border-color:rgba(94,139,61,0.1);">
                                                <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                                    @if ($entry->changedByUser)
                                                        <span class="inline-block px-2 py-0.5 rounded-full text-white text-[10px] font-bold"
                                                              style="background:{{ $entry->changedByUser->role === 'admin' ? '#4f46e5' : '#3F6B2A' }}">
                                                            {{ $entry->changedByUser->role === 'admin' ? 'Admin' : 'Officer' }}
                                                        </span>
                                                        <span class="text-[11px] font-semibold" style="color:#5F6B57;">{{ $entry->changedByUser->name }}</span>
                                                    @else
                                                        <span class="inline-block px-2 py-0.5 rounded-full bg-gray-400 text-white text-[10px] font-bold">System</span>
                                                    @endif
                                                </div>
                                                <p class="m-0 font-medium whitespace-pre-wrap" style="color:#1F3318;">{{ $entry->remarks }}</p>
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
    </div>
</x-app-layout>

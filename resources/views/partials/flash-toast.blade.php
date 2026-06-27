@php
    $toastMessage = session('success') ?? session('warning');
    $toastType = session('success') ? 'success' : (session('warning') ? 'warning' : null);
@endphp

@if ($toastMessage && $toastType)
    <style>[x-cloak] { display: none !important; }</style>
    <div
        x-data="{ show: true }"
        x-cloak
        x-show="show"
        x-init="setTimeout(() => show = false, 8000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-8"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-20 right-5 z-[10050] w-[calc(100%-2.5rem)] max-w-md rounded-xl px-4 py-3 shadow-lg"
        style="{{ $toastType === 'success'
            ? 'background:#EAF1DD; border:1.5px solid rgba(94,139,61,0.35); color:#1F3318; box-shadow:0 8px 24px rgba(63,107,42,0.18);'
            : 'background:#FEF9E7; border:1.5px solid rgba(180,130,20,0.35); color:#5C4A12; box-shadow:0 8px 24px rgba(120,90,10,0.15);' }}"
        role="alert"
        aria-live="polite"
    >
        <div class="flex items-start gap-3">
            <span
                class="flex shrink-0 items-center justify-center w-7 h-7 rounded-full text-white text-sm font-bold"
                style="background:{{ $toastType === 'success' ? '#3F6B2A' : '#B8860B' }};"
            >{{ $toastType === 'success' ? '✓' : '!' }}</span>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-semibold leading-snug">{{ $toastMessage }}</p>
                @if (session('tracking_code'))
                    <p class="mt-1.5 font-mono text-xs" style="color:{{ $toastType === 'success' ? '#3F6B2A' : '#8B6914' }};">
                        Tracking code: <strong>{{ session('tracking_code') }}</strong>
                    </p>
                @endif
            </div>
            <button
                type="button"
                @click="show = false"
                class="shrink-0 leading-none border-none bg-transparent cursor-pointer text-lg font-light p-0"
                style="color:#7B8F69;"
                aria-label="Dismiss notification"
            >&times;</button>
        </div>
    </div>
@endif

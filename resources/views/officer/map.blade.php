<x-officer-admin-layout active-nav="map" page-title="Incident Map">
    <div class="p-4 sm:p-6 max-w-[1200px] mx-auto w-full space-y-4">
        <div>
            <h1 class="text-2xl font-bold text-portal-on-surface m-0">Incident Map</h1>
            <p class="text-sm text-portal-on-surface-variant mt-1 mb-0">{{ count($mapMarkers) }} report{{ count($mapMarkers) === 1 ? '' : 's' }} with location data</p>
        </div>

        @if (count($mapMarkers) === 0)
            <div class="rounded-xl bg-white border border-portal-outline-variant p-8 text-center shadow-sm">
                <span class="material-symbols-outlined text-4xl text-portal-on-surface-variant">map</span>
                <p class="mt-3 text-sm text-portal-on-surface-variant m-0">No mapped incidents yet. Reports need a pinned map location when submitted.</p>
            </div>
        @endif

        <div class="rounded-xl bg-white border border-portal-outline-variant p-4 shadow-sm">
            <div class="async-panel is-ready" id="crime-map-panel">
                <x-skeleton type="map" class="async-panel__skeleton" />
                <div
                    id="crime-map"
                    data-officer-map
                    data-map-panel="crime-map-panel"
                    data-map-markers='@json($mapMarkers)'
                    class="h-[28rem] sm:h-[32rem] w-full rounded-lg z-[1]"
                ></div>
            </div>
        </div>
    </div>
</x-officer-admin-layout>

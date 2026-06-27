<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Crime Map</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="mb-4 text-sm text-gray-600">{{ $reports->count() }} reports with location data</p>
                <div class="async-panel" id="crime-map-panel">
                    <x-skeleton type="map" class="async-panel__skeleton" />
                    <div id="crime-map" class="h-[32rem] w-full rounded-lg z-0"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer>
            (async () => {
                const panel = document.getElementById('crime-map-panel');
                try {
                    if (window.ecrmsWhenReady) await window.ecrmsWhenReady();
                    await Promise.all([
                        window.ecrmsLoadStylesheet('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'),
                        window.ecrmsLoadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'),
                    ]);

                    const reports = @json($mapReports);
                    const map = L.map('crime-map').setView([0.3476, 32.5825], 7);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
                    const bounds = [];
                    reports.forEach(r => {
                        const lat = parseFloat(r.crime?.latitude ?? r.location_latitude);
                        const lng = parseFloat(r.crime?.longitude ?? r.location_longitude);
                        if (!lat || !lng) return;
                        bounds.push([lat, lng]);
                        L.marker([lat, lng]).addTo(map).bindPopup(
                            `<strong>#${r.report_id ?? r.id}</strong><br>${r.crime?.category_name ?? r.crime_category?.name ?? ''}<br><em>${r.status}</em><br><a href="/officer/reports/${r.report_id ?? r.id}">View</a>`
                        );
                    });
                    if (bounds.length) map.fitBounds(bounds, { padding: [40, 40] });
                    setTimeout(() => map.invalidateSize(), 200);
                } finally {
                    window.ecrmsMarkAsyncPanelReady?.(panel);
                }
            })();
        </script>
    @endpush
</x-app-layout>

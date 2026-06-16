<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Crime Map</h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="mb-4 text-sm text-gray-600">{{ $reports->count() }} reports with location data</p>
                <div id="crime-map" class="h-[32rem] w-full rounded-lg z-0"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const reports = @json($mapReports);
            const map = L.map('crime-map').setView([0.3476, 32.5825], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            const bounds = [];
            reports.forEach(r => {
                const lat = parseFloat(r.location_latitude);
                const lng = parseFloat(r.location_longitude);
                if (!lat || !lng) return;
                bounds.push([lat, lng]);
                L.marker([lat, lng]).addTo(map).bindPopup(
                    `<strong>#${r.id}</strong><br>${r.crime_category?.name ?? ''}<br><em>${r.status}</em><br><a href="/officer/reports/${r.id}">View</a>`
                );
            });
            if (bounds.length) map.fitBounds(bounds, { padding: [40, 40] });
        </script>
    @endpush
</x-app-layout>

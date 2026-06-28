import { loadScript, loadStylesheet, markAsyncPanelReady, whenEcrmsReady } from './performance';

const LEAFLET_CSS = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
const LEAFLET_JS = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
const DEFAULT_VIEW = [1.3733, 32.2903];
const DEFAULT_ZOOM = 7;

let leafletReady = null;

function loadLeaflet() {
    if (leafletReady) {
        return leafletReady;
    }

    leafletReady = Promise.all([
        loadStylesheet(LEAFLET_CSS),
        loadScript(LEAFLET_JS),
    ]);

    return leafletReady;
}

function fixLeafletIcons() {
    if (typeof L === 'undefined' || !L.Icon?.Default) {
        return;
    }

    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });
}

export function markerCoords(marker) {
    const lat = parseFloat(marker?.latitude ?? marker?.location_latitude ?? marker?.crime?.latitude);
    const lng = parseFloat(marker?.longitude ?? marker?.location_longitude ?? marker?.crime?.longitude);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        return null;
    }

    return { lat, lng };
}

export async function initOfficerMap(options) {
    const {
        element,
        panelId = null,
        markers = [],
        markerStyle = 'marker',
        defaultView = DEFAULT_VIEW,
        defaultZoom = DEFAULT_ZOOM,
        zoomControl = true,
    } = options;

    if (!element) {
        return null;
    }

    await whenEcrmsReady();
    await loadLeaflet();

    if (typeof L === 'undefined') {
        throw new Error('Leaflet failed to load');
    }

    fixLeafletIcons();

    const map = L.map(element, { zoomControl }).setView(defaultView, defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    markers.forEach((marker) => {
        const coords = markerCoords(marker);
        if (!coords) {
            return;
        }

        bounds.push([coords.lat, coords.lng]);

        const label = marker.category_name ?? marker.crime?.category_name ?? 'Environmental incident';
        const reportId = marker.report_id ?? marker.id;
        const status = marker.status ?? '';
        const url = marker.url ?? `/officer/reports/${reportId}`;
        const popup = `<strong>#${reportId}</strong><br>${label}<br><em>${status}</em><br><a href="${url}">View</a>`;

        if (markerStyle === 'circle') {
            L.circleMarker([coords.lat, coords.lng], {
                radius: 7,
                color: '#116c4a',
                fillColor: '#ef4444',
                fillOpacity: 0.9,
                weight: 2,
            }).addTo(map).bindPopup(popup);
        } else {
            L.marker([coords.lat, coords.lng]).addTo(map).bindPopup(popup);
        }
    });

    if (bounds.length === 1) {
        map.setView(bounds[0], 13);
    } else if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }

    const panel = panelId ? document.getElementById(panelId) : null;

    requestAnimationFrame(() => {
        map.invalidateSize();
        markAsyncPanelReady(panel);
    });

    setTimeout(() => map.invalidateSize(), 300);

    return map;
}

function readMarkers(element) {
    const raw = element.getAttribute('data-map-markers');

    if (!raw) {
        return [];
    }

    try {
        const parsed = JSON.parse(raw);
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}

function bootOfficerMaps() {
    document.querySelectorAll('[data-officer-map]').forEach((element) => {
        if (element.dataset.mapBooted === 'true') {
            return;
        }

        element.dataset.mapBooted = 'true';

        initOfficerMap({
            element,
            panelId: element.getAttribute('data-map-panel') || null,
            markers: readMarkers(element),
            markerStyle: element.getAttribute('data-marker-style') || 'marker',
            zoomControl: element.getAttribute('data-zoom-control') !== 'false',
        }).catch((error) => {
            console.error('Officer map failed to initialize', error);
            markAsyncPanelReady(document.getElementById(element.getAttribute('data-map-panel')));
        });
    });
}

window.ecrmsReportMapCoords = markerCoords;
window.ecrmsInitLeafletMap = (legacyOptions) => initOfficerMap({
    element: document.getElementById(legacyOptions.mapElementId),
    panelId: legacyOptions.panelId ?? null,
    markers: legacyOptions.reports ?? [],
    markerStyle: legacyOptions.markerStyle ?? 'marker',
    defaultView: legacyOptions.defaultView ?? DEFAULT_VIEW,
    defaultZoom: legacyOptions.defaultZoom ?? DEFAULT_ZOOM,
    zoomControl: legacyOptions.markerStyle !== 'circle',
});

window.ecrmsInitOfficerMap = initOfficerMap;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootOfficerMaps, { once: true });
} else {
    bootOfficerMaps();
}

document.addEventListener('ecrms:boot-officer-maps', bootOfficerMaps);

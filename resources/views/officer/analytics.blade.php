<x-officer-admin-layout active-nav="analytics" page-title="Analytics">
    <div class="p-4 sm:p-6 max-w-[1200px] mx-auto w-full">
        <h1 class="text-2xl font-bold text-portal-on-surface mb-6">Analytics</h1>

        @php
            $hasData = $byCategory->isNotEmpty() || $byStatus->isNotEmpty() || $byPriority->isNotEmpty();
        @endphp

        @if (! $hasData)
            <div class="rounded-xl border border-portal-outline-variant bg-white p-10 text-center text-sm text-portal-on-surface-variant">
                No report data available yet. Charts will appear once reports are submitted.
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl bg-white border border-portal-outline-variant p-6 shadow-sm async-panel" id="category-chart-panel">
                    <h3 class="font-semibold text-portal-ink m-0">Reports by category</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <div class="relative mt-4 h-72">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="rounded-xl bg-white border border-portal-outline-variant p-6 shadow-sm async-panel" id="status-chart-panel">
                    <h3 class="font-semibold text-portal-ink m-0">Reports by status</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <div class="relative mt-4 h-72">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="rounded-xl bg-white border border-portal-outline-variant p-6 shadow-sm lg:col-span-2 async-panel" id="priority-chart-panel">
                    <h3 class="font-semibold text-portal-ink m-0">Reports by priority</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <div class="relative mt-4 h-64 max-w-md mx-auto">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if ($hasData)
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const panelIds = ['category-chart-panel', 'status-chart-panel', 'priority-chart-panel'];
                const panels = panelIds.map((id) => document.getElementById(id));

                const markReady = () => {
                    panels.forEach((panel) => window.ecrmsMarkAsyncPanelReady?.(panel));
                };

                try {
                    if (window.ecrmsWhenReady) {
                        await window.ecrmsWhenReady();
                    }

                    if (window.ecrmsLoadScript) {
                        await window.ecrmsLoadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js');
                    } else {
                        await new Promise((resolve, reject) => {
                            const script = document.createElement('script');
                            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
                            script.defer = true;
                            script.onload = resolve;
                            script.onerror = reject;
                            document.body.appendChild(script);
                        });
                    }

                    if (typeof Chart === 'undefined') {
                        throw new Error('Chart.js failed to load');
                    }

                    const chartDefaults = { responsive: true, maintainAspectRatio: false };

                    new Chart(document.getElementById('categoryChart'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($byCategory->pluck('name')),
                            datasets: [{
                                data: @json($byCategory->pluck('total')),
                                backgroundColor: ['#059669', '#0d9488', '#14b8a6', '#2dd4bf', '#5eead4', '#99f6e4', '#ccfbf1'],
                            }],
                        },
                        options: chartDefaults,
                    });

                    new Chart(document.getElementById('statusChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($byStatus->pluck('status')),
                            datasets: [{
                                label: 'Reports',
                                data: @json($byStatus->pluck('total')),
                                backgroundColor: '#116c4a',
                            }],
                        },
                        options: {
                            ...chartDefaults,
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                        },
                    });

                    new Chart(document.getElementById('priorityChart'), {
                        type: 'pie',
                        data: {
                            labels: @json($byPriority->pluck('priority')),
                            datasets: [{
                                data: @json($byPriority->pluck('total')),
                                backgroundColor: ['#6b7280', '#eab308', '#f97316', '#dc2626'],
                            }],
                        },
                        options: chartDefaults,
                    });
                } catch (error) {
                    console.error('Analytics charts failed:', error);
                } finally {
                    markReady();
                }
            });
        </script>
    @endif
</x-officer-admin-layout>

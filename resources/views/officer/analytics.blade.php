<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Analytics</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow async-panel" id="category-chart-panel">
                    <h3 class="font-semibold text-gray-900">Reports by category</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <canvas id="categoryChart" class="mt-4 max-h-72"></canvas>
                </div>
                <div class="rounded-lg bg-white p-6 shadow async-panel" id="status-chart-panel">
                    <h3 class="font-semibold text-gray-900">Reports by status</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <canvas id="statusChart" class="mt-4 max-h-72"></canvas>
                </div>
                <div class="rounded-lg bg-white p-6 shadow lg:col-span-2 async-panel" id="priority-chart-panel">
                    <h3 class="font-semibold text-gray-900">Reports by priority</h3>
                    <x-skeleton type="chart" class="async-panel__skeleton mt-4" />
                    <canvas id="priorityChart" class="mt-4 max-h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer>
            (async () => {
                const panels = ['category-chart-panel', 'status-chart-panel', 'priority-chart-panel']
                    .map((id) => document.getElementById(id));
                try {
                    if (window.ecrmsWhenReady) await window.ecrmsWhenReady();
                    await window.ecrmsLoadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js');

                    const chartDefaults = { responsive: true, maintainAspectRatio: true };

                    new Chart(document.getElementById('categoryChart'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($byCategory->pluck('name')),
                            datasets: [{ data: @json($byCategory->pluck('total')), backgroundColor: ['#059669','#0d9488','#14b8a6','#2dd4bf','#5eead4','#99f6e4','#ccfbf1'] }]
                        },
                        options: chartDefaults
                    });

                    new Chart(document.getElementById('statusChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($byStatus->pluck('status')),
                            datasets: [{ label: 'Reports', data: @json($byStatus->pluck('total')), backgroundColor: '#059669' }]
                        },
                        options: { ...chartDefaults, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                    });

                    new Chart(document.getElementById('priorityChart'), {
                        type: 'pie',
                        data: {
                            labels: @json($byPriority->pluck('priority')),
                            datasets: [{ data: @json($byPriority->pluck('total')), backgroundColor: ['#6b7280','#eab308','#f97316','#dc2626'] }]
                        },
                        options: chartDefaults
                    });
                } finally {
                    panels.forEach((panel) => window.ecrmsMarkAsyncPanelReady(panel));
                }
            })();
        </script>
    @endpush
</x-app-layout>

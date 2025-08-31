<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Appointment Statistics</h3>
                    <p class="text-blue-100 text-sm">Real-time appointment data</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Live Data
                </span>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-gray-600 font-medium">Filter by timeframe</div>
            <div class="flex items-center space-x-2">
                <select id="appointment-stats-timeframe" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    <option value="month" selected>Last Month</option>
                    <option value="week">This Week</option>
                    <option value="today">Today</option>
                    <option value="all">All Time</option>
                </select>
                <select id="appointment-chart-type" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    <option value="bar" selected>Bar Chart</option>
                    <option value="line">Line Chart</option>
                    <option value="doughnut">Doughnut Chart</option>
                </select>
            </div>
        </div>
        
        <div id="appointment-stats-empty" class="text-center py-8 hidden">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-sm text-gray-500 mt-2">No appointment data available</p>
        </div>
        
        <div id="appointment-stats-chart-wrap" style="height: 260px;" class="relative">
            <canvas id="appointment-chart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('appointment-chart');
        if (!canvas) { return; }
        const ctx = canvas.getContext('2d');
        const wrap = document.getElementById('appointment-stats-chart-wrap');
        const emptyMsg = document.getElementById('appointment-stats-empty');
        const select = document.getElementById('appointment-stats-timeframe');
        const chartTypeSelect = document.getElementById('appointment-chart-type');
        let chart;

        function render(labels, values) {
            const hasData = Array.isArray(values) && values.some(v => Number(v) > 0);
            if (!hasData) {
                wrap.classList.add('hidden');
                emptyMsg.classList.remove('hidden');
                if (chart) { chart.destroy(); }
                return;
            }
            emptyMsg.classList.add('hidden');
            wrap.classList.remove('hidden');
            if (chart) { chart.destroy(); }

            const chartType = chartTypeSelect.value;

            const datasets = [{
                label: 'Appointments',
                data: values,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',  // Blue
                    'rgba(16, 185, 129, 0.8)',  // Green
                    'rgba(245, 158, 11, 0.8)',  // Yellow
                    'rgba(239, 68, 68, 0.8)',   // Red
                    'rgba(139, 92, 246, 0.8)'   // Purple
                ],
                borderColor: chartType === 'line' ? 'rgba(59, 130, 246, 1)' : 'rgba(59, 130, 246, 0.8)',
                borderWidth: chartType === 'line' ? 3 : 1,
                fill: chartType === 'line' ? true : false,
                tension: chartType === 'line' ? 0.4 : 0,
            }];

            const options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(59, 130, 246, 0.8)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `Appointments: ${context.parsed.y || context.parsed}`;
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: chartType === 'line' ? 6 : 0,
                        hoverRadius: chartType === 'line' ? 8 : 0,
                    }
                }
            };

            if (chartType === 'bar' || chartType === 'line') {
                options.scales = {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(0, 0, 0, 0.6)',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: 'rgba(0, 0, 0, 0.6)',
                            font: {
                                size: 12
                            }
                        }
                    }
                };
            }

            chart = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: options,
            });
        }

        async function load(timeframe) {
            try {
                const res = await fetch(`{{ route('dashboard.appointments.stats') }}?timeframe=${encodeURIComponent(timeframe)}`);
                if (!res.ok) throw new Error('Failed to load');
                const json = await res.json();
                render(json.labels || [], json.values || []);
            } catch (e) {
                render([], []);
            }
        }

        select.addEventListener('change', function() { load(this.value); });
        chartTypeSelect.addEventListener('change', function() { load(select.value); });
        load(select.value);
    });
</script>
@endpush

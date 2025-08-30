<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Appointment Statistics</h3>
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm text-gray-600">By status</div>
            <select id="appointment-stats-timeframe" class="text-sm border-gray-300 rounded-md">
                <option value="month" selected>Last Month</option>
                <option value="week">This Week</option>
                <option value="today">Today</option>
                <option value="all">All Time</option>
            </select>
            <select id="appointment-chart-type" class="text-sm border-gray-300 rounded-md ml-2">
                <option value="bar" selected>Bar Chart</option>
                <option value="line">Filled Line Chart</option>
                <option value="doughnut">Doughnut Chart</option>
            </select>
        </div>
        <div id="appointment-stats-empty" class="text-sm text-gray-500 hidden">No appointment data available.</div>
        <div id="appointment-stats-chart-wrap" style="height: 260px;">
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
        const chartTypeSelect = document.getElementById('appointment-chart-type'); // New line
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

            const chartType = chartTypeSelect.value; // Get selected chart type

            const datasets = [{
                label: 'Appointments',
                data: values,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                // Add borderColor for line charts
                borderColor: 'rgba(54, 162, 235, 1)',
                // Add fill for line charts
                fill: chartType === 'line' ? true : false,
            }];

            const options = {
                responsive: true,
                maintainAspectRatio: false,
            };

            // Add scales for bar and line charts
            if (chartType === 'bar' || chartType === 'line') {
                options.scales = {
                    y: {
                        beginAtZero: true
                    }
                };
            }

            chart = new Chart(ctx, {
                type: chartType, // Use dynamic chart type
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
        chartTypeSelect.addEventListener('change', function() { load(select.value); }); // New event listener
        load(select.value);
    });
</script>
@endpush

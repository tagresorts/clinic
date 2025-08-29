<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Appointment Statistics</h3>
        <div class="relative h-64">
            <canvas id="appointment-chart"></canvas>
            <div id="appointment-chart-empty" class="absolute inset-0 flex items-center justify-center text-gray-500 hidden">
                No appointment data to display
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function initAppointmentChart() {
        const render = () => {
            const canvas = document.getElementById('appointment-chart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            const labels = @json(array_keys(($data['appointments_by_status'] ?? collect())->toArray()));
            const values = @json(array_values(($data['appointments_by_status'] ?? collect())->toArray()));

            const isEmpty = !values || values.length === 0 || values.every(v => Number(v) === 0);
            const emptyEl = document.getElementById('appointment-chart-empty');

            if (isEmpty) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                if (ctx) {
                    // clear any previous chart drawing if exists
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }
                return;
            } else if (emptyEl) {
                emptyEl.classList.add('hidden');
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Appointments',
                        data: values,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', render);
        } else {
            render();
        }
    })();
</script>
@endpush

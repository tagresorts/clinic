<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Appointment Statistics</h3>
        @php
            $labels = array_keys(($data['appointments_by_status'] ?? collect())->toArray());
            $values = array_values(($data['appointments_by_status'] ?? collect())->toArray());
        @endphp
        @if (empty($labels))
            <div class="text-sm text-gray-500">No appointment data available.</div>
        @else
            <div style="height: 260px;">
                <canvas id="appointment-chart"></canvas>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = @json($labels);
        const values = @json($values);
        if (!labels || labels.length === 0) { return; }
        const canvas = document.getElementById('appointment-chart');
        if (!canvas) { return; }
        const ctx = canvas.getContext('2d');
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
    });
</script>
@endpush

<x-app-layout>
    @push('styles')
    <style>
    .cards-grid{display:grid;grid-template-columns:repeat(12, minmax(0,1fr));grid-auto-rows:10px;gap:12px}
    .card{grid-column:span 6;background:#fff;border-radius:.5rem;box-shadow:0 1px 2px rgba(0,0,0,.05);padding:1rem;resize:both;overflow:auto;min-width:200px;min-height:120px}
    .card.small{grid-column:span 6}
    .card.large{grid-column:span 6}
    .card-header{font-weight:600;margin-bottom:.5rem;cursor:move}
    </style>
    @endpush
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-2">
                    <select id="timeframe-select" class="border rounded px-2 py-1 text-sm">
                        <option value="today" selected>Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                    <button id="save-layout-btn" class="text-sm px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Layout</button>
                    <form action="{{ route('dashboard.resetLayout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="text-sm px-3 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">Reset</button>
                    </form>
                </div>
            </div>

            <div class="cards-grid" id="dashboard-cards">
                <div class="card" data-id="card-kpi">
                    <div class="card-header">KPIs</div>
                    <div id="kpi-content" class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Today's Appointments:</span>
                            <span id="kpi-appointments" class="font-semibold">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Active Patients:</span>
                            <span id="kpi-patients" class="font-semibold">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Low Stock Items:</span>
                            <span id="kpi-stock" class="font-semibold text-red-600">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Expiring Soon:</span>
                            <span id="kpi-expiring" class="font-semibold text-orange-600">-</span>
                        </div>
                    </div>
                </div>
                <div class="card" data-id="card-appointments">
                    <div class="card-header">Appointments</div>
                    <p class="text-gray-600 text-sm">Upcoming appointments list.</p>
                </div>
                <div class="card" data-id="card-alerts">
                    <div class="card-header">Alerts</div>
                    <p class="text-gray-600 text-sm">Important notifications.</p>
                </div>
                <div class="card" data-id="card-report">
                    <div class="card-header">Mini Report</div>
                    <p class="text-gray-600 text-sm">Chart or metrics go here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function() {
document.addEventListener('DOMContentLoaded', function () {
    const saveLayoutBtn = document.getElementById('save-layout-btn');
    const container = document.getElementById('dashboard-cards');
    let sortable;

    function formatDate(date) {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        const year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    function getAppointmentsRange(timeframe) {
        const start = new Date();
        const end = new Date();
        if (timeframe === 'month') end.setDate(start.getDate() + 30);
        else if (timeframe === 'week') end.setDate(start.getDate() + 7);
        else end.setDate(start.getDate() + 1);
        return { start: formatDate(start), end: formatDate(end) };
    }

    function buildAppointmentsUrl() {
        const tf = timeframeSelect.value;
        const range = getAppointmentsRange(tf);
        const url = new URL('{{ route('appointments.summary', [], false) }}', window.location.origin);
        url.searchParams.set('start_date', range.start);
        url.searchParams.set('end_date', range.end);
        return url;
    }

    // Initialize a lightweight month calendar that fetches events
    try {
        if (!window.FullCalendar && calendarEl) {
            calendarEl.innerHTML = '<div class="text-gray-500">Calendar unavailable. <a class="text-indigo-600" href="{{ route('appointments.calendar', [], false) }}">Open full calendar</a></div>';
        }
        const calendar = window.FullCalendar ? new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            height: 400,
            events: {
                url: '{{ route('appointments.feed', [], false) }}',
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    window.open(info.event.url, '_blank');
                }
            }
        }) : null;
        if (calendar) calendar.render();
    } catch (e) {
        console.warn('FullCalendar not available or failed to initialize:', e);
    }

    // Show loading state
    if (container) {
        container.innerHTML = '<div class="text-center text-gray-400 py-8"><i class="fas fa-spinner fa-spin"></i><p class="mt-2">Loading upcoming appointments...</p></div>';
    }

    function loadAppointments() {
      const url = buildAppointmentsUrl();
      fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
      })
      .then(response => {
          if (!response.ok) throw new Error('Failed to load appointments');
          return response.json();
      })
      .then(appointments => {
          container.innerHTML = '';
          if (!appointments.length) {
              container.innerHTML = '<p class="text-gray-500">No upcoming appointments in this period.</p>';
              return;
          }
          const list = document.createElement('ul');
          list.className = 'divide-y divide-gray-100 border border-gray-100 rounded-lg';
          appointments.slice(0, 10).forEach(appt => {
              const li = document.createElement('li');
              li.className = 'p-3 hover:bg-gray-50 transition';
              li.innerHTML = `
                  <a href="${appt.url}" class="flex items-center justify-between">
                      <div>
                          <p class="font-medium text-gray-900">${appt.patient_name}</p>
                          <p class="text-sm text-gray-600">${appt.time} • ${appt.type} • ${appt.dentist_name}</p>
                      </div>
                      <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">${appt.status}</span>
                  </a>`;
              list.appendChild(li);
          });
          container.appendChild(list);
      })
      .catch(err => {
          container.innerHTML = `<p class="text-red-500">${err.message}</p>`;
          console.error(err);
      });
    }

    // Lightweight polling to keep KPIs fresh (every 30s)
    function refreshKpis() {
        // No-op in minimal mode
        return;
    }

    // Alerts
    function refreshAlerts() {
        if (!alertsList) return;
        fetch('{{ route('dashboard.alerts', [], false) }}', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(items => {
                alertsList.innerHTML = '';
                if (!items.length) {
                    alertsList.innerHTML = '<li class="text-gray-500">No alerts</li>';
                    return;
                }
                items.forEach(a => {
                    const li = document.createElement('li');
                    const color = a.level === 'danger' ? 'text-red-600' : a.level === 'warning' ? 'text-yellow-600' : 'text-gray-700';
                    li.className = color;
                    li.textContent = a.message;
                    alertsList.appendChild(li);
                });
            })
            .catch(() => {});
    }

    // Mini Report (Chart.js)
    let miniChart;
    function loadMiniReport() {
        const ctx = document.getElementById('mini-report-canvas');
        if (!ctx || !window.Chart) return;
        fetch('{{ route('dashboard.mini-report.appointments', [], false) }}', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                const labels = Object.keys(data);
                const values = Object.values(data);
                if (miniChart) {
                    miniChart.data.labels = labels;
                    miniChart.data.datasets[0].data = values;
                    miniChart.update();
                    return;
                }
                miniChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Appointments',
                            data: values,
                            backgroundColor: 'rgba(99, 102, 241, 0.6)'
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
                });
            })
            .catch(() => {});
    }

    // GridStack init and layout load/save
    function initGrid() {
        if (window.__dashboardSortableInit) return;
        if (!container) return;
        sortable = new Sortable(container, {
            animation: 150,
            handle: '.card-header',
            ghostClass: 'opacity-50',
        });
        window.__dashboardSortableInit = true;
        // Load saved order
        fetch('{{ route('dashboard.layout', [], false) }}', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(items => {
                const order = Array.isArray(items) ? items.map(i => i.id) : [];
                if (!order.length) return;
                const map = {};
                order.forEach(id => { map[id] = true; });
                Array.from(container.children).forEach(child => {
                    const id = child.getAttribute('data-id');
                    if (!map[id]) return; // unknown in saved order
                });
                order.forEach(id => {
                    const el = container.querySelector(`[data-id="${id}"]`);
                    if (el) container.appendChild(el);
                });
            }).catch(() => {});
        // Save order
        if (saveLayoutBtn) {
            saveLayoutBtn.addEventListener('click', function() {
                const layout = Array.from(container.children).map((el, idx) => ({ id: el.getAttribute('data-id'), x: idx, y: 0, w: 6, h: 2 }));
                fetch('{{ route('dashboard.saveLayout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ layout })
                }).then(res => {
                    if (res.ok) alert('Layout Saved!');
                    else alert('Error saving layout');
                });
            });
        }
    }

    // (Widget library removed in minimal reset to avoid JS conflicts)

    // Events
    // Initial minimal init
    initGrid();
});
})();
</script>
@endpush
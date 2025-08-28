<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@12.2.2/dist/gridstack.min.css">
    @endpush
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Dental Clinic</h1>
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

            <!-- GridStack Dashboard -->
            <div class="grid-stack" id="dashboard-grid">
                <!-- KPI Widget -->
                <div class="grid-stack-item" gs-id="kpi" gs-x="0" gs-y="0" gs-w="12" gs-h="2">
                    <div class="grid-stack-item-content">
                        @include('dashboard._kpi_panel')
                    </div>
                </div>

                <!-- Appointments Widget -->
                <div class="grid-stack-item" gs-id="appointments" gs-x="0" gs-y="2" gs-w="8" gs-h="6">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Appointments</h3>
                            <a href="{{ route('appointments.calendar', [], false) }}" class="text-sm text-indigo-600 hover:text-indigo-800">View calendar</a>
                        </div>
                        <div id="dashboard-calendar" class="mb-4"></div>
                        <div id="dashboard-appointments-list" class="space-y-3"></div>
                    </div>
                </div>

                <!-- Patient Widget -->
                <div class="grid-stack-item" gs-id="patient" gs-x="8" gs-y="2" gs-w="4" gs-h="4">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <x-widgets.patient-section :patientData="$patientData" />
                    </div>
                </div>

                <!-- Admin Notices Widget -->
                <div class="grid-stack-item" gs-id="admin-notices" gs-x="8" gs-y="6" gs-w="4" gs-h="3">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Admin Notices</h3>
                        <x-widgets.admin-notices />
                    </div>
                </div>

                <!-- Alerts Widget -->
                <div class="grid-stack-item" gs-id="alerts" gs-x="0" gs-y="8" gs-w="4" gs-h="3">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alerts</h3>
                        <ul id="alerts-list" class="space-y-2 text-sm text-gray-700"></ul>
                    </div>
                </div>

                <!-- Mini Report Widget -->
                <div class="grid-stack-item" gs-id="mini-report" gs-x="4" gs-y="8" gs-w="8" gs-h="3">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Appointments (Last 7 days)</h3>
                        <canvas id="mini-report-canvas" height="120"></canvas>
                    </div>
                </div>

                <!-- Quick Actions Widget -->
                <div class="grid-stack-item" gs-id="quick-actions" gs-x="0" gs-y="11" gs-w="4" gs-h="2">
                    <div class="grid-stack-item-content bg-white p-6 rounded-lg shadow h-full">
                        <x-widgets.quick-actions />
                    </div>
                </div>
            </div>

            <!-- Widget Library Modal -->
            <div id="widget-library" class="fixed inset-0 bg-black bg-opacity-30 hidden items-center justify-center z-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">Widget Library</h3>
                        <button id="widget-lib-close" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                    <div class="space-y-3 text-sm">
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="kpi" checked> <span>KPI Summary</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="appointments" checked> <span>Appointments</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="patient" checked> <span>Patient Section</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="admin-notices" checked> <span>Admin Notices</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="alerts" checked> <span>Alerts</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="mini-report" checked> <span>Mini Report</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" class="widget-toggle" data-widget="quick-actions" checked> <span>Quick Actions</span></label>
                    </div>
                    <div class="mt-4 flex items-center justify-end space-x-2">
                        <button id="widget-lib-save" class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Save</button>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-6 right-6 z-30">
                <button id="open-widget-lib" class="px-3 py-2 bg-gray-800 text-white rounded shadow hover:bg-gray-700 text-sm">Widgets</button>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
(function() {
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('dashboard-appointments-list');
    const kpiPanel = document.getElementById('kpi-summary-panel');
    const calendarEl = document.getElementById('dashboard-calendar');
    const timeframeSelect = document.getElementById('timeframe-select');
    const alertsList = document.getElementById('alerts-list');
    const saveLayoutBtn = document.getElementById('save-layout-btn');
    const widgetLib = document.getElementById('widget-library');
    const widgetLibOpen = document.getElementById('open-widget-lib');
    const widgetLibClose = document.getElementById('widget-lib-close');
    const widgetLibSave = document.getElementById('widget-lib-save');
    let grid;

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
        if (!kpiPanel) return;
        const tf = timeframeSelect.value || 'today';
        const url = new URL('{{ route('dashboard.kpis.html', [], false) }}', window.location.origin);
        url.searchParams.set('timeframe', tf);
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(r => {
                if (!r.ok) throw new Error('Failed to refresh KPIs');
                return r.text();
            })
            .then(html => {
                // Replace only the KPI grid contents
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const newPanel = temp.querySelector('#kpi-summary-panel');
                if (newPanel) {
                    kpiPanel.replaceWith(newPanel);
                }
            })
            .catch(e => console.warn(e.message));
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
        if (!window.GridStack) return;
        if (grid) return; // avoid duplicate init
        grid = GridStack.init({ float: true, cellHeight: '8rem', minRow: 1 }, '#dashboard-grid');
        // Load saved layout
        fetch('{{ route('dashboard.layout', [], false) }}', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(layout => {
                try {
                    // Apply visibility by removing hidden widgets from grid
                    const visMap = {};
                    (layout || []).forEach(w => { visMap[w.id] = (w.is_visible ?? true); });
                    document.querySelectorAll('#dashboard-grid .grid-stack-item').forEach(item => {
                        const id = item.getAttribute('gs-id');
                        if (visMap.hasOwnProperty(id) && !visMap[id]) {
                            item.style.display = 'none';
                        }
                    });
                    // Load positions/sizes
                    grid.load((layout || []).map(w => ({ id: w.id, x: w.x, y: w.y, w: w.w, h: w.h })));
                } catch (e) {
                    console.warn('Grid load skipped:', e.message);
                }
            });
        // Save
        if (saveLayoutBtn) {
            saveLayoutBtn.addEventListener('click', function() {
                const serialized = grid.save();
                fetch('{{ route('dashboard.saveLayout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ layout: serialized })
                }).then(res => {
                    if (res.ok) {
                        alert('Layout Saved!');
                    } else {
                        alert('Error saving layout');
                    }
                });
            });
        }
    }

    // Widget Library Handlers
    function setWidgetModal(open) {
        if (!widgetLib) return;
        widgetLib.classList.toggle('hidden', !open);
        widgetLib.classList.toggle('flex', open);
    }

    widgetLibOpen.addEventListener('click', () => setWidgetModal(true));
    widgetLibClose.addEventListener('click', () => setWidgetModal(false));
    widgetLib.addEventListener('click', (e) => { if (e.target === widgetLib) setWidgetModal(false); });

    widgetLibSave.addEventListener('click', () => {
        const toggles = Array.from(document.querySelectorAll('.widget-toggle'));
        const widgets = toggles.map(t => ({ id: t.dataset.widget, is_visible: t.checked }));
        // Reflect immediately in UI
        widgets.forEach(w => {
            const el = document.querySelector(`#dashboard-grid .grid-stack-item[gs-id="${w.id}"]`);
            if (el) el.style.display = w.is_visible ? '' : 'none';
        });
        // Persist
        fetch('{{ route('dashboard.widgets.visibility') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin',
            body: JSON.stringify({ widgets })
        }).then(() => setWidgetModal(false));
    });

    // Events
    timeframeSelect.addEventListener('change', function() {
        refreshKpis();
        loadAppointments();
    });

    // Initial loads
    loadAppointments();
    refreshKpis();
    refreshAlerts();
    loadMiniReport();
    initGrid();

    // Polling - gentle cadence
    setInterval(refreshKpis, 30000);
    setInterval(refreshAlerts, 45000);
    setInterval(loadMiniReport, 60000);
});
})();
</script>
@endpush
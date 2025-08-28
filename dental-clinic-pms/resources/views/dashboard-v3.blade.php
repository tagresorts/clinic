<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dental Clinic</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-search"></i></button>
                    <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-user-circle fa-lg"></i></button>
                </div>
            </div>

            <!-- KPI Widgets (dynamic) -->
            @include('dashboard._kpi_panel')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Sidebar -->
                <div class="lg:col-span-3 space-y-6">
                    <x-widgets.quick-actions />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow h-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Appointments</h3>
                            <a href="{{ route('appointments.calendar', [], false) }}" class="text-sm text-indigo-600 hover:text-indigo-800">View calendar</a>
                        </div>
                        <div id="dashboard-calendar" class="mb-4"></div>
                        <div id="dashboard-appointments-list" class="space-y-3">
                            @if(isset($upcomingAppointments) && $upcomingAppointments->count())
                                <ul class="divide-y divide-gray-100 border border-gray-100 rounded-lg">
                                    @foreach($upcomingAppointments as $appt)
                                        <li class="p-3 hover:bg-gray-50 transition">
                                            <a href="{{ route('appointments.show', $appt) }}" class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $appt->patient->full_name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $appt->appointment_datetime->format('M j, Y g:i A') }} • {{ $appt->appointment_type }} • Dr. {{ $appt->dentist->name }}</p>
                                                </div>
                                                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">{{ ucfirst(str_replace('_', ' ', $appt->status)) }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <x-widgets.patient-section :patientData="$patientData" />
                    </div>
                     <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Admin Notices</h3>
                        <x-widgets.admin-notices />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('dashboard-appointments-list');
    const kpiPanel = document.getElementById('kpi-summary-panel');
    const calendarEl = document.getElementById('dashboard-calendar');

    function formatDate(date) {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        const year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    const today = new Date();
    const inSevenDays = new Date();
    inSevenDays.setDate(today.getDate() + 7);

    const startDate = formatDate(today);
    const endDate = formatDate(inSevenDays);

    const url = new URL('{{ route('appointments.summary', [], false) }}', window.location.origin);
    console.debug('Dashboard summary URL:', url.toString());
    url.searchParams.append('start_date', startDate);
    url.searchParams.append('end_date', endDate);

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
            container.innerHTML = '<p class="text-gray-500">No upcoming appointments in the next 7 days.</p>';
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
                </a>
            `;
            list.appendChild(li);
        });

        container.appendChild(list);
    })
    .catch(err => {
        container.innerHTML = `<p class="text-red-500">${err.message}</p>`;
        console.error(err);
    });

    // Lightweight polling to keep KPIs fresh (every 30s)
    function refreshKpis() {
        if (!kpiPanel) return;
        fetch('{{ route('dashboard.kpis.html', [], false) }}', {
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

    setInterval(refreshKpis, 30000);
    // Initial refresh shortly after load to ensure parity
    setTimeout(refreshKpis, 5000);
});
</script>
@endpush
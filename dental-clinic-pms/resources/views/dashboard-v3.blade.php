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

            <!-- KPI Widgets -->
            <div id="kpi-summary-panel" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @foreach ($kpis as $key => $kpi)
                    @if(in_array($key, ['todays_appointments', 'active_patients', 'daily_revenue', 'pending_payments']))
                        <x-kpi.widget :title="$kpi['title']" :value="$kpiData[$key]" :icon="$kpi['icon']" />
                    @endif
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Sidebar -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Dentists</h3>
                        <x-staff.activity-panel :staff="$staff" />
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Billing Summary</h3>
                        <x-widgets.billing-summary />
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow h-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Appointments</h3>
                            <a href="{{ route('appointments.calendar', [], false) }}" class="text-sm text-indigo-600 hover:text-indigo-800">View calendar</a>
                        </div>
                        <div id="dashboard-appointments-list" class="space-y-3">
                            <div class="text-center text-gray-400 py-8">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p class="mt-2">Loading upcoming appointments...</p>
                            </div>
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
    url.searchParams.append('start_date', startDate);
    url.searchParams.append('end_date', endDate);

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        }
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
});
</script>
@endpush

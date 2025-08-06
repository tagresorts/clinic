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
                         <h3 class="text-lg font-semibold text-gray-800 mb-4">Appointments</h3>
                        <!-- Calendar Placeholder -->
                        <div class="text-center text-gray-400 py-16">
                            <i class="fas fa-calendar-alt fa-4x"></i>
                            <p class="mt-4">Appointments Calendar Placeholder</p>
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

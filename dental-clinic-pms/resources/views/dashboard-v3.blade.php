<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex h-screen bg-gray-100">
        <!-- Left Sidebar -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold">Dentist/Staff Activity</h3>
            </div>
            <div id="staff-activity-panel" class="p-4">
                <x-staff.activity-panel :staff="$staff" />
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Panel -->
            <div class="bg-white shadow">
                <div class="p-4">
                    <h3 class="text-lg font-semibold">KPIs Summary</h3>
                    <div id="kpi-summary-panel" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-4">
                        @foreach ($kpis as $key => $kpi)
                            <x-kpi.widget :title="$kpi['title']" :value="$kpiData[$key]" :icon="$kpi['icon']" />
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Center Section -->
            <div class="flex-1 p-4">
                <h3 class="text-lg font-semibold">Appointments Calendar</h3>
                <div id="calendar-section" class="bg-white p-4 rounded-lg shadow-sm mt-4 h-full">
                    <!-- Calendar will go here -->
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="bg-white shadow">
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">Billing Summary</h3>
                        <div id="billing-summary-widget" class="bg-white p-4 rounded-lg shadow-sm mt-4">
                            <x-widgets.billing-summary />
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Admin Notices & Reminders</h3>
                        <div id="admin-notices-widget" class="bg-white p-4 rounded-lg shadow-sm mt-4">
                            <x-widgets.admin-notices />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

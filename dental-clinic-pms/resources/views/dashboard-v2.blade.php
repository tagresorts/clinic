<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex h-screen bg-gray-100">
        <!-- Left Sidebar -->
        @can(config('dashboard.panels.staff_activity.permission'))
            <div class="w-64 bg-white shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold">Dentist/Staff Activity</h3>
                </div>
                <div id="staff-activity-panel" class="p-4">
                    <x-staff.activity-panel :staff="$staff" />
                </div>
            </div>
        @endcan

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
                <div id="calendar-section" class="bg-white p-4 rounded-lg shadow-sm mt-4">
                    <!-- Calendar will go here -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

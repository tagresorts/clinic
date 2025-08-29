<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="p-4 bg-white rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Total Patients</h3>
        <p class="text-3xl font-bold text-gray-900">{{ $admin_data['total_patients'] }}</p>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">New Patients This Month</h3>
        <p class="text-3xl font-bold text-gray-900">{{ $admin_data['new_patients_this_month'] }}</p>
    </div>
</div>

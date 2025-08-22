@props(['title', 'icon'])

<div class="bg-white p-6 rounded-lg shadow-lg text-gray-800">
    <div class="flex items-center mb-4">
        <i class="fas {{ $icon }} fa-lg mr-3 text-indigo-600"></i>
        <h4 class="font-semibold">{{ $title }}</h4>
    </div>
    <div class="space-y-2">
        <a href="{{ route('patients.create') }}" class="flex items-center text-sm text-gray-600 hover:text-indigo-600">
            <i class="fas fa-user-plus fa-fw mr-2"></i>
            <span>Add New Patient</span>
        </a>
        <a href="{{ route('appointments.create') }}" class="flex items-center text-sm text-gray-600 hover:text-indigo-600">
            <i class="fas fa-calendar-plus fa-fw mr-2"></i>
            <span>Schedule Appointment</span>
        </a>
        <a href="{{ route('treatment-plans.create') }}" class="flex items-center text-sm text-gray-600 hover:text-indigo-600">
            <i class="fas fa-file-medical fa-fw mr-2"></i>
            <span>Create Treatment Plan</span>
        </a>
    </div>
</div>

@props(['patientData'])

<div class="space-y-6">
    <div class="relative">
        <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-gray-800 mb-3">Recently Visited</h4>
        <ul class="space-y-2">
            @foreach($patientData['recent_registrations']->take(2) as $patient)
                <li class="flex justify-between items-center text-gray-700 hover:bg-gray-50 p-2 rounded-md cursor-pointer">
                    <span>{{ $patient->full_name }}</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <h4 class="font-semibold text-gray-800 mb-3">Upcoming Recall</h4>
        <ul class="space-y-2">
            <li class="flex justify-between items-center text-gray-700 hover:bg-gray-50 p-2 rounded-md cursor-pointer">
                <span>Daniel King</span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </li>
        </ul>
    </div>

    <div>
        <h4 class="font-semibold text-gray-800 mb-3">New Patients</h4>
        <ul class="space-y-2">
             <li class="flex justify-between items-center text-gray-700 hover:bg-gray-50 p-2 rounded-md cursor-pointer">
                <span>Sophia Adams</span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </li>
        </ul>
    </div>
</div>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Today's Schedule</h3>
        @if(($data['todays_appointments'] ?? collect())->count() > 0)
            <div class="space-y-3">
                @foreach(($data['todays_appointments'] ?? collect()) as $appointment)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="font-semibold">{{ $appointment->appointment_datetime->format('g:i A') }}</p>
                        <p class="text-sm">{{ $appointment->patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->appointment_type }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No appointments scheduled for today.</p>
        @endif
    </div>
</div>

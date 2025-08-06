@props(['staff'])

<div class="space-y-4">
    <div class="flex justify-between text-sm font-semibold text-gray-500">
        <span>APPOINTMENTS</span>
        <span>App pr.</span>
    </div>
    <ul class="space-y-3">
        @foreach ($staff as $member)
            <li class="flex items-center justify-between">
                <span class="font-medium text-gray-800">Dr. {{ $member->name }}</span>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-600">{{ $member->todays_appointments_count ?? 0 }}</span>
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                </div>
            </li>
        @endforeach
    </ul>
    <button class="w-full mt-4 py-2 px-4 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition-colors">View Schedule</button>
</div>
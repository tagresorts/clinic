<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Recent User Activity</h3>
        <div class="space-y-4">
            @foreach(($data['recent_activities'] ?? []) as $activity)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                        <span class="text-{{ $activity['color'] }}-600">•</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

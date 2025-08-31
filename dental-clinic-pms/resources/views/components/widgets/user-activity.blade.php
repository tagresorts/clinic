<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-4 h-full overflow-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">User Activity</h3>
        <div class="space-y-2">
            @foreach (($data['user_activity'] ?? []) as $activity)
                <div class="flex items-center p-2 text-sm text-gray-700 bg-gray-100 rounded-lg">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                    <div class="flex-1">
                        <div class="font-medium">{{ $activity['user'] ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">{{ $activity['action'] ?? 'Action' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

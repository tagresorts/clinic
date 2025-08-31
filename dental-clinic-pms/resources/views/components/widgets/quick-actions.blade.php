<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-4 h-full overflow-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Quick Actions</h3>
        <div class="space-y-2">
            @foreach (($data['quick_actions'] ?? []) as $action)
                <a href="{{ $action['url'] }}" class="flex items-center p-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    @if(!empty($action['icon']))
                        {!! $action['icon'] !!}
                    @else
                        <span class="w-5 h-5 mr-2">•</span>
                    @endif
                    <span class="ml-2">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
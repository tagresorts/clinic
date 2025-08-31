<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-4 h-full overflow-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Inventory Alerts</h3>
        <div class="space-y-2">
            @foreach (($data['inventory_alerts'] ?? []) as $alert)
                <div class="flex items-center p-2 text-sm text-gray-700 bg-red-50 border border-red-200 rounded-lg">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                    <div class="flex-1">
                        <div class="font-medium">{{ $alert['item'] ?? 'Item' }}</div>
                        <div class="text-xs text-red-600">Low stock: {{ $alert['quantity'] ?? 0 }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

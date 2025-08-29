<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Inventory Alerts</h3>
        <p class="text-3xl font-bold text-red-600">{{ $data['low_stock_items'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">items are low on stock</p>
    </div>
</div>

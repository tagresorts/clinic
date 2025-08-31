<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Inventory Alerts</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-3xl font-bold text-red-600">{{ $data['low_stock_items'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">low stock</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-amber-600">{{ $data['expiring_items'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">expiring soon</p>
            </div>
        </div>
    </div>
</div>

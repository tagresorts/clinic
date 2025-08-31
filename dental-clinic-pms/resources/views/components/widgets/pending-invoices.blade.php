<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Pending Invoices</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $data['overdue_invoices'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">invoices are overdue</p>
    </div>
</div>

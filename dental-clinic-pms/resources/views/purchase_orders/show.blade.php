<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">PO-{{ $purchaseOrder->id }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <div class="text-sm text-gray-600">Supplier</div>
                        <div class="font-medium">{{ $purchaseOrder->supplier?->supplier_name }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <div class="text-sm text-gray-600">Status</div>
                            <div class="capitalize">{{ $purchaseOrder->status }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Expected Date</div>
                            <div>{{ optional($purchaseOrder->expected_date)->format('M d, Y') ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Total</div>
                            <div>{{ number_format($purchaseOrder->total_cost, 2) }}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($purchaseOrder->items as $line)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $line->inventoryItem?->item_name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $line->description ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $line->quantity_ordered }}</td>
                                    <td class="px-6 py-4 text-sm">{{ number_format($line->unit_cost, 2) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ number_format($line->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

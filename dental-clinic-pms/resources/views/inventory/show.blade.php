<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $item->item_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Code</p>
                            <p class="text-lg">{{ $item->item_code ?? '—' }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('inventory.edit', $item) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-xs font-semibold rounded">Edit</a>
                            <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-white border text-xs font-semibold rounded">Back</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Supplier</p>
                            <p class="text-lg">
                                @if($item->supplier)
                                    <a class="text-indigo-600" href="{{ route('suppliers.show', $item->supplier) }}">{{ $item->supplier->supplier_name }}</a>
                                @else — @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Quantity / Reorder</p>
                            <p class="text-lg">{{ $item->quantity_in_stock }} / {{ $item->reorder_level }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Expiry</p>
                            <p class="text-lg">{{ $item->has_expiry ? optional($item->expiry_date)->format('M d, Y') : '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

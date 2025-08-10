<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Inventory') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-wrap gap-2 items-center">
                            <x-text-input name="search" placeholder="Search items..." value="{{ request('search') }}" />
                            <label class="inline-flex items-center text-sm">
                                <input type="checkbox" name="low_stock" value="1" class="mr-1" @checked(request('low_stock'))>
                                Low stock
                            </label>
                            <label class="inline-flex items-center text-sm">
                                <input type="checkbox" name="expiring" value="1" class="mr-1" @checked(request('expiring'))>
                                Expiring soon
                            </label>
                            <x-primary-button>Filter</x-primary-button>
                        </form>
                        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded">Add Item</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ $item->item_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->item_code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->supplier)
                                            <a class="text-indigo-600" href="{{ route('suppliers.show', $item->supplier) }}">{{ $item->supplier->supplier_name }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span @class(['px-2 py-0.5 rounded text-xs', 'bg-red-100 text-red-700'=> $item->isLowStock(), 'bg-gray-100 text-gray-700'=> !$item->isLowStock()])>
                                            {{ $item->quantity_in_stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->reorder_level }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->has_expiry)
                                            <span @class(['px-2 py-0.5 rounded text-xs', 'bg-yellow-100 text-yellow-800'=> $item->isExpired(), 'bg-green-100 text-green-700'=> !$item->isExpired()])>
                                                {{ optional($item->expiry_date)->format('M d, Y') ?? '—' }}
                                            </span>
                                        @else — @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('inventory.show', $item) }}" class="text-indigo-600">View</a>
                                        <a href="{{ route('inventory.edit', $item) }}" class="text-gray-700">Edit</a>
                                        <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No items found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $items->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

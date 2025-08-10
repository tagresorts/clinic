<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Stock Movements') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($movements as $m)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $m->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $m->item->item_name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm capitalize">{{ $m->type }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $m->quantity }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $m->reference ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $m->notes ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $movements->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

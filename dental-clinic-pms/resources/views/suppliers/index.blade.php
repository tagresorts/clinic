<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Suppliers') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <form method="GET" action="{{ route('suppliers.index') }}" class="flex space-x-2">
                            <x-text-input name="search" placeholder="Search suppliers..." value="{{ request('search') }}" />
                            <x-primary-button>Search</x-primary-button>
                        </form>
                        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded">Add Supplier</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td class="px-6 py-4">{{ $supplier->supplier_name }}</td>
                                    <td class="px-6 py-4">{{ $supplier->contact_person_name }}</td>
                                    <td class="px-6 py-4">{{ $supplier->email }}</td>
                                    <td class="px-6 py-4">{{ $supplier->phone }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="text-indigo-600">View</a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-gray-700">Edit</a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Delete this supplier?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No suppliers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $suppliers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

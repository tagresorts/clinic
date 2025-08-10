<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $supplier->supplier_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-lg">{{ $supplier->email ?? '—' }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-xs font-semibold rounded">Edit</a>
                            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border text-xs font-semibold rounded">Back</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-lg">{{ $supplier->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-lg">{{ $supplier->address ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

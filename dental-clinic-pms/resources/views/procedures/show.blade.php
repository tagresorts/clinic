<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Procedure Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-4">
                        <p><span class="font-medium">Name:</span> {{ $procedure->name }}</p>
                        <p><span class="font-medium">Description:</span> {{ $procedure->description ?? 'N/A' }}</p>
                        <p><span class="font-medium">Cost:</span> ₱{{ number_format($procedure->cost, 2) }}</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-secondary-button onclick="window.history.back()" class="mr-2">
                            {{ __('Back') }}
                        </x-secondary-button>
                        <x-primary-button onclick="window.location.href='{{ route('procedures.edit', $procedure) }}'">
                            {{ __('Edit Procedure') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

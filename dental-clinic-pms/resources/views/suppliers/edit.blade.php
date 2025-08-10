<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Supplier') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="supplier_name" :value="__('Supplier Name')" />
                            <x-text-input id="supplier_name" name="supplier_name" class="mt-1 block w-full" value="{{ old('supplier_name', $supplier->supplier_name) }}" required />
                            <x-input-error :messages="$errors->get('supplier_name')" class="mt-2" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" class="mt-1 block w-full" value="{{ old('email', $supplier->email) }}" />
                            </div>
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" name="phone" class="mt-1 block w-full" value="{{ old('phone', $supplier->phone) }}" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 rounded">{{ old('address', $supplier->address) }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <x-primary-button>Update</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

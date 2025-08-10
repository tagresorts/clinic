<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Inventory Item') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="item_name" :value="__('Item Name')" />
                            <x-text-input id="item_name" name="item_name" class="mt-1 block w-full" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="item_code" :value="__('Item Code')" />
                                <x-text-input id="item_code" name="item_code" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <select id="supplier_id" name="supplier_id" class="mt-1 block w-full border-gray-300 rounded">
                                    <option value="">—</option>
                                    @foreach($suppliers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="quantity_in_stock" :value="__('Quantity')" />
                                <x-text-input id="quantity_in_stock" name="quantity_in_stock" type="number" class="mt-1 block w-full" value="0" />
                            </div>
                            <div>
                                <x-input-label for="reorder_level" :value="__('Reorder Level')" />
                                <x-text-input id="reorder_level" name="reorder_level" type="number" class="mt-1 block w-full" value="0" />
                            </div>
                            <div>
                                <x-input-label for="unit_cost" :value="__('Unit Cost')" />
                                <x-text-input id="unit_cost" name="unit_cost" type="number" step="0.01" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="has_expiry" value="1" class="mr-2"> Has expiry
                            </label>
                            <div>
                                <x-input-label for="expiry_date" :value="__('Expiry Date')" />
                                <x-text-input id="expiry_date" name="expiry_date" type="date" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <x-primary-button>Save</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@props(['procedure' => null, 'method' => 'POST', 'action' => ''])

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
            :value="old('name', $procedure?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Description -->
    <div class="mt-4">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $procedure?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- Cost -->
    <div class="mt-4">
        <x-input-label for="cost" :value="__('Cost')" />
        <x-text-input id="cost" class="block mt-1 w-full" type="number" name="cost" 
            :value="old('cost', $procedure?->cost)" required step="0.01" />
        <x-input-error :messages="$errors->get('cost')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-6">
        <x-secondary-button type="button" onclick="window.history.back()" class="mr-2">
            {{ __('Cancel') }}
        </x-secondary-button>
        <x-primary-button>
            {{ $procedure ? __('Update Procedure') : __('Create Procedure') }}
        </x-primary-button>
    </div>
</form>

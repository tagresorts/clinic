<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operational Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('ops-settings.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <x-input-label for="treatment_plan_reminder_days" :value="__('Treatment Plan Reminder Days')" />
                            <x-text-input id="treatment_plan_reminder_days" name="treatment_plan_reminder_days" type="number" class="mt-1 block w-full" :value="old('treatment_plan_reminder_days', $settings['treatment_plan_reminder_days'] ?? '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('treatment_plan_reminder_days')" />
                        </div>

                        <div class="mb-4">
                            <label for="expiration_threshold" class="block text-sm font-medium text-gray-700">Expiration Threshold (in days)</label>
                            <input type="number" name="expiration_threshold" id="expiration_threshold" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('expiration_threshold', $settings['expiration_threshold'] ?? 30) }}">
                            <x-input-error class="mt-2" :messages="$errors->get('expiration_threshold')" />
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>

                            @if (session('success'))
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ session('success') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

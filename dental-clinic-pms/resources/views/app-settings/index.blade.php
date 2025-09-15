<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('App Settings - Clinic Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('app-settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <x-input-label for="clinic_name" :value="__('Clinic Name')" />
                            <x-text-input id="clinic_name" name="clinic_name" type="text" class="mt-1 block w-full" :value="old('clinic_name', $settings['clinic_name'] ?? '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('clinic_name')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="clinic_address" :value="__('Address')" />
                            <textarea id="clinic_address" name="clinic_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="3">{{ old('clinic_address', $settings['clinic_address'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('clinic_address')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="clinic_phone" :value="__('Phone')" />
                                <x-text-input id="clinic_phone" name="clinic_phone" type="text" class="mt-1 block w-full" :value="old('clinic_phone', $settings['clinic_phone'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('clinic_phone')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_email" :value="__('Email')" />
                                <x-text-input id="clinic_email" name="clinic_email" type="email" class="mt-1 block w-full" :value="old('clinic_email', $settings['clinic_email'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('clinic_email')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="clinic_fb" :value="__('Facebook URL')" />
                                <x-text-input id="clinic_fb" name="clinic_fb" type="url" class="mt-1 block w-full" :value="old('clinic_fb', $settings['clinic_fb'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('clinic_fb')" />
                            </div>
                            <div>
                                <x-input-label for="clinic_instagram" :value="__('Instagram URL')" />
                                <x-text-input id="clinic_instagram" name="clinic_instagram" type="url" class="mt-1 block w-full" :value="old('clinic_instagram', $settings['clinic_instagram'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('clinic_instagram')" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="clinic_website" :value="__('Website URL')" />
                            <x-text-input id="clinic_website" name="clinic_website" type="url" class="mt-1 block w-full" :value="old('clinic_website', $settings['clinic_website'] ?? '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('clinic_website')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="operation_hours" :value="__('Operation Hours')" />
                            <textarea id="operation_hours" name="operation_hours" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="3" placeholder="e.g. Mon–Fri 9:00 AM – 6:00 PM; Sat 9:00 AM – 1:00 PM">{{ old('operation_hours', $settings['operation_hours'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('operation_hours')" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="clinic_logo" :value="__('Clinic Logo')" />
                            <input id="clinic_logo" name="clinic_logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-700" />
                            @if(!empty($settings['clinic_logo_url']))
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500">Current logo preview:</p>
                                    <img src="{{ $settings['clinic_logo_url'] }}" alt="Clinic Logo" class="h-16 object-contain bg-gray-50 p-2 border rounded" />
                                </div>
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('clinic_logo')" />
                        </div>

                        <div class="flex items-center gap-4 mt-6">
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


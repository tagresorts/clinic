@props(['patient' => null, 'method' => 'POST', 'action' => ''])

<form id="patientForm" method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="space-y-6">
        <!-- Personal Information -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" 
                        :value="old('first_name', $patient?->first_name)" required autofocus />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" 
                        :value="old('last_name', $patient?->last_name)" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                        :value="old('email', $patient?->email)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div>
                    <x-input-label for="phone" :value="__('Phone')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" 
                        :value="old('phone', $patient?->phone)" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Date of Birth -->
                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                    <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" 
                        :value="old('date_of_birth', $patient?->date_of_birth?->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $patient?->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $patient?->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $patient?->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" 
                        :value="old('address', $patient?->address)" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contact</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Emergency Contact Name -->
                <div>
                    <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                    <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" 
                        :value="old('emergency_contact_name', $patient?->emergency_contact_name)" required />
                    <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                </div>

                <!-- Emergency Contact Phone -->
                <div>
                    <x-input-label for="emergency_contact_phone" :value="__('Emergency Contact Phone')" />
                    <x-text-input id="emergency_contact_phone" class="block mt-1 w-full" type="text" name="emergency_contact_phone" 
                        :value="old('emergency_contact_phone', $patient?->emergency_contact_phone)" required />
                    <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                </div>

                <!-- Emergency Contact Relationship -->
                <div class="md:col-span-2">
                    <x-input-label for="emergency_contact_relationship" :value="__('Emergency Contact Relationship')" />
                    <x-text-input id="emergency_contact_relationship" class="block mt-1 w-full" type="text" name="emergency_contact_relationship" 
                        :value="old('emergency_contact_relationship', $patient?->emergency_contact_relationship)" required />
                    <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Medical Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Allergies -->
                <div>
                    <x-input-label for="allergies" :value="__('Allergies')" />
                    <textarea id="allergies" name="allergies" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('allergies', $patient?->allergies) }}</textarea>
                    <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                </div>

                <!-- Medical Conditions -->
                <div>
                    <x-input-label for="medical_conditions" :value="__('Medical Conditions')" />
                    <textarea id="medical_conditions" name="medical_conditions" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('medical_conditions', $patient?->medical_conditions) }}</textarea>
                    <x-input-error :messages="$errors->get('medical_conditions')" class="mt-2" />
                </div>

                <!-- Current Medications -->
                <div class="md:col-span-2">
                    <x-input-label for="current_medications" :value="__('Current Medications')" />
                    <textarea id="current_medications" name="current_medications" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('current_medications', $patient?->current_medications) }}</textarea>
                    <x-input-error :messages="$errors->get('current_medications')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Insurance Information -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Insurance Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Insurance Provider -->
                <div>
                    <x-input-label for="insurance_provider" :value="__('Insurance Provider')" />
                    <x-text-input id="insurance_provider" class="block mt-1 w-full" type="text" name="insurance_provider" 
                        :value="old('insurance_provider', $patient?->insurance_provider)" />
                    <x-input-error :messages="$errors->get('insurance_provider')" class="mt-2" />
                </div>

                <!-- Insurance Policy Number -->
                <div>
                    <x-input-label for="insurance_policy_number" :value="__('Insurance Policy Number')" />
                    <x-text-input id="insurance_policy_number" class="block mt-1 w-full" type="text" name="insurance_policy_number" 
                        :value="old('insurance_policy_number', $patient?->insurance_policy_number)" />
                    <x-input-error :messages="$errors->get('insurance_policy_number')" class="mt-2" />
                </div>

                <!-- Insurance Group Number -->
                <div>
                    <x-input-label for="insurance_group_number" :value="__('Insurance Group Number')" />
                    <x-text-input id="insurance_group_number" class="block mt-1 w-full" type="text" name="insurance_group_number" 
                        :value="old('insurance_group_number', $patient?->insurance_group_number)" />
                    <x-input-error :messages="$errors->get('insurance_group_number')" class="mt-2" />
                </div>

                <!-- Insurance Expiry Date -->
                <div>
                    <x-input-label for="insurance_expiry_date" :value="__('Insurance Expiry Date')" />
                    <x-text-input id="insurance_expiry_date" class="block mt-1 w-full" type="date" name="insurance_expiry_date" 
                        :value="old('insurance_expiry_date', $patient?->insurance_expiry_date?->format('Y-m-d'))" />
                    <x-input-error :messages="$errors->get('insurance_expiry_date')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Dental Notes -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Dental Notes</h3>
            <div class="grid grid-cols-1 gap-6">
                <!-- Dental History -->
                <div>
                    <x-input-label for="dental_history" :value="__('Dental History')" />
                    <textarea id="dental_history" name="dental_history" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('dental_history', $patient?->dental_history) }}</textarea>
                    <x-input-error :messages="$errors->get('dental_history')" class="mt-2" />
                </div>

                <!-- Previous Treatments -->
                <div>
                    <x-input-label for="previous_treatments" :value="__('Previous Treatments')" />
                    <textarea id="previous_treatments" name="previous_treatments" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('previous_treatments', $patient?->previous_treatments) }}</textarea>
                    <x-input-error :messages="$errors->get('previous_treatments')" class="mt-2" />
                </div>

                <!-- Dental Notes -->
                <div>
                    <x-input-label for="dental_notes" :value="__('Dental Notes')" />
                    <textarea id="dental_notes" name="dental_notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('dental_notes', $patient?->dental_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('dental_notes')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-secondary-button type="button" onclick="window.history.back()" class="mr-2">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button>
                {{ $patient ? __('Update Patient') : __('Create Patient') }}
            </x-primary-button>
        </div>
    </div>
</form>

@props(['appointment' => null, 'patients' => [], 'dentists' => []])

<div class="space-y-6">
    <!-- Appointment Details -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Appointment Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Patient -->
            <div>
                <x-input-label for="patient_id" :value="__('Patient')" />
                <select id="patient_id" name="patient_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $appointment?->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
            </div>

            <!-- Dentist -->
            <div>
                <x-input-label for="dentist_id" :value="__('Dentist')" />
                <select id="dentist_id" name="dentist_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select Dentist</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}" {{ old('dentist_id', $appointment?->dentist_id) == $dentist->id ? 'selected' : '' }}>
                            {{ $dentist->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
            </div>

            <!-- Appointment Date -->
            <div>
                <x-input-label for="appointment_date" :value="__('Date')" />
                <x-text-input id="appointment_date" class="block mt-1 w-full" type="date" name="appointment_date"
                    :value="old('appointment_date', $appointment?->appointment_datetime?->format('Y-m-d'))" required />
                <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
            </div>

            <!-- Appointment Time -->
            <div>
                <x-input-label for="appointment_time" :value="__('Time')" />
                <x-text-input id="appointment_time" class="block mt-1 w-full" type="time" name="appointment_time"
                    :value="old('appointment_time', $appointment?->appointment_datetime?->format('H:i'))" required />
                <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
            </div>

            <!-- Duration -->
            <div>
                <x-input-label for="duration_minutes" :value="__('Duration (minutes)')" />
                <x-text-input id="duration_minutes" class="block mt-1 w-full" type="number" name="duration_minutes"
                    :value="old('duration_minutes', $appointment?->duration_minutes ?? 30)" required min="15" step="15" />
                <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
            </div>

            <!-- Appointment Type -->
            <div>
                <x-input-label for="appointment_type" :value="__('Appointment Type')" />
                <x-text-input id="appointment_type" class="block mt-1 w-full" type="text" name="appointment_type"
                    :value="old('appointment_type', $appointment?->appointment_type)" required />
                <x-input-error :messages="$errors->get('appointment_type')" class="mt-2" />
            </div>

            <!-- Reason For Visit -->
            <div class="md:col-span-2">
                <x-input-label for="reason_for_visit" :value="__('Reason For Visit')" />
                <textarea id="reason_for_visit" name="reason_for_visit" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('reason_for_visit', $appointment?->reason_for_visit) }}</textarea>
                <x-input-error :messages="$errors->get('reason_for_visit')" class="mt-2" />
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
        <div class="grid grid-cols-1 gap-6">
            <div>
                <x-input-label for="appointment_notes" :value="__('Internal Notes')" />
                <textarea id="appointment_notes" name="appointment_notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('appointment_notes', $appointment?->appointment_notes) }}</textarea>
                <x-input-error :messages="$errors->get('appointment_notes')" class="mt-2" />
            </div>
        </div>
    </div>

    @if ($appointment)
    <!-- Status -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
                <x-input-label for="status" :value="__('Appointment Status')" />
                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    @foreach(App\Models\Appointment::getStatuses() as $status)
                        <option value="{{ $status }}" {{ old('status', $appointment?->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            @if($appointment->status === 'cancelled')
            <div>
                <x-input-label for="cancellation_reason" :value="__('Cancellation Reason')" />
                <textarea id="cancellation_reason" name="cancellation_reason" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('cancellation_reason', $appointment?->cancellation_reason) }}</textarea>
                <x-input-error :messages="$errors->get('cancellation_reason')" class="mt-2" />
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

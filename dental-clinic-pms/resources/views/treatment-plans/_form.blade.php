@props(['plan' => null, 'patients' => [], 'dentists' => [], 'method' => 'POST', 'action' => ''])

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="space-y-6">
        <!-- Patient and Dentist Information -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Patient & Dentist</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient -->
                <div>
                    <x-input-label for="patient_id" :value="__('Patient')" />
                    <select id="patient_id" name="patient_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $plan?->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->fullName }}
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
                            <option value="{{ $dentist->id }}" {{ old('dentist_id', $plan?->dentist_id) == $dentist->id ? 'selected' : '' }}>
                                {{ $dentist->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('dentist_id')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Plan Details -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Plan Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plan Title -->
                <div>
                    <x-input-label for="plan_title" :value="__('Plan Title')" />
                    <x-text-input id="plan_title" class="block mt-1 w-full" type="text" name="plan_title" 
                        :value="old('plan_title', $plan?->plan_title)" required />
                    <x-input-error :messages="$errors->get('plan_title')" class="mt-2" />
                </div>

                <!-- Estimated Duration (Sessions) -->
                <div>
                    <x-input-label for="estimated_duration_sessions" :value="__('Estimated Duration (Sessions)')" />
                    <x-text-input id="estimated_duration_sessions" class="block mt-1 w-full" type="number" name="estimated_duration_sessions" 
                        :value="old('estimated_duration_sessions', $plan?->estimated_duration_sessions)" required />
                    <x-input-error :messages="$errors->get('estimated_duration_sessions')" class="mt-2" />
                </div>

                <!-- Diagnosis -->
                <div class="md:col-span-2">
                    <x-input-label for="diagnosis" :value="__('Diagnosis')" />
                    <textarea id="diagnosis" name="diagnosis" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('diagnosis', $plan?->diagnosis) }}</textarea>
                    <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                </div>

                <!-- Priority -->
                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="low" {{ old('priority', $plan?->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $plan?->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $plan?->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $plan?->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="proposed" {{ old('status', $plan?->status) === 'proposed' ? 'selected' : '' }}>Proposed</option>
                        <option value="patient_approved" {{ old('status', $plan?->status) === 'patient_approved' ? 'selected' : '' }}>Patient Approved</option>
                        <option value="in_progress" {{ old('status', $plan?->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $plan?->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $plan?->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Proposed Procedures & Cost -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Proposed Procedures & Cost</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Proposed Procedures (Checkboxes) -->
                <div>
                    <x-input-label :value="__('Proposed Procedures')" />
                    <div id="procedure_checkboxes" class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($procedures as $procedure)
                            <label for="procedure_{{ $procedure->id }}" class="inline-flex items-center">
                                <input type="checkbox" id="procedure_{{ $procedure->id }}" name="procedure_ids[]" value="{{ $procedure->id }}" data-cost="{{ $procedure->cost }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ in_array($procedure->id, old('procedure_ids', $plan ? $plan->procedures->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ $procedure->name }} (₱{{ number_format($procedure->cost, 2) }})</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('procedure_ids')" class="mt-2" />
                </div>

                <!-- Total Estimated Cost Display -->
                <div>
                    <x-input-label for="total_estimated_cost_display" :value="__('Total Estimated Cost')" />
                    <x-text-input id="total_estimated_cost_display" class="block mt-1 w-full" type="text" readonly />
                </div>

                <!-- Estimated Cost (Hidden Input) -->
                <x-text-input id="estimated_cost" class="hidden" type="number" name="estimated_cost" 
                    :value="old('estimated_cost', $plan?->estimated_cost)" required step="0.01" />
            </div>
        </div>

        <!-- Additional Notes -->
        @if($plan)
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tentative Appointments</h3>
            <div id="tentative-appointments-container" class="space-y-4">
                <!-- Dynamic date fields will be inserted here -->
            </div>
        </div>
        @endif

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Notes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Treatment Notes -->
                <div>
                    <x-input-label for="treatment_notes" :value="__('Treatment Notes')" />
                    <textarea id="treatment_notes" name="treatment_notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('treatment_notes', $plan?->treatment_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('treatment_notes')" class="mt-2" />
                </div>

                <!-- Patient Concerns -->
                <div>
                    <x-input-label for="patient_concerns" :value="__('Patient Concerns')" />
                    <textarea id="patient_concerns" name="patient_concerns" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('patient_concerns', $plan?->patient_concerns) }}</textarea>
                    <x-input-error :messages="$errors->get('patient_concerns')" class="mt-2" />
                </div>

                <!-- Dentist Notes -->
                <div class="md:col-span-2">
                    <x-input-label for="dentist_notes" :value="__('Dentist Notes')" />
                    <textarea id="dentist_notes" name="dentist_notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('dentist_notes', $plan?->dentist_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('dentist_notes')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-secondary-button type="button" onclick="window.history.back()" class="mr-2">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button>
                {{ $plan ? __('Update Treatment Plan') : __('Create Treatment Plan') }}
            </x-primary-button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const procedureCheckboxes = document.querySelectorAll('input[name="procedure_ids[]"]');
        const estimatedCostInput = document.getElementById('estimated_cost');
        const totalEstimatedCostDisplay = document.getElementById('total_estimated_cost_display');

        function calculateTotalCost() {
            let totalCost = 0;
            procedureCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalCost += parseFloat(checkbox.dataset.cost);
                }
            });
            estimatedCostInput.value = totalCost.toFixed(2);
            totalEstimatedCostDisplay.value = '₱ ' + totalCost.toFixed(2);
        }

        procedureCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotalCost);
        });

        // Initial calculation on page load
        calculateTotalCost();

        const sessionsInput = document.getElementById('estimated_duration_sessions');
        const appointmentsContainer = document.getElementById('tentative-appointments-container');
        const existingAppointments = @json($tentativeAppointments ?? []);

        function generateAppointmentFields() {
            const sessions = parseInt(sessionsInput.value, 10) || 0;
            appointmentsContainer.innerHTML = '';

            for (let i = 0; i < sessions; i++) {
                const appointment = existingAppointments[i] || {};
                const date = appointment.appointment_datetime ? new Date(appointment.appointment_datetime).toISOString().slice(0, 16) : '';

                const field = `
                    <div class="flex items-center space-x-4">
                        <label for="appointment_date_${i}" class="block text-sm font-medium text-gray-700">Session ${i + 1}</label>
                        <input type="datetime-local" id="appointment_date_${i}" name="appointment_dates[]" value="${date}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                `;
                appointmentsContainer.insertAdjacentHTML('beforeend', field);
            }
        }

        if (sessionsInput && appointmentsContainer) {
            sessionsInput.addEventListener('input', generateAppointmentFields);
            generateAppointmentFields();
        }
    });
</script>
@endpush

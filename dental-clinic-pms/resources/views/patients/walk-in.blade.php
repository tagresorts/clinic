<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Walk-in Appointment and Treatment') }} for {{ $patient->first_name }} {{ $patient->last_name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('patients.store-walk-in', $patient) }}" method="POST" id="walk-in-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Appointment Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Appointment Details</h3>
                                <div>
                                    <label for="appointment_datetime" class="block text-sm font-medium text-gray-700">Appointment Time</label>
                                    <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" value="{{ now()->format('Y-m-d\TH:i') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="dentist_id" class="block text-sm font-medium text-gray-700">Dentist</label>
                                    <select id="dentist_id" name="dentist_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        @foreach($dentists as $dentist)
                                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" value="30" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div id="conflict-message" class="hidden p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                    <span class="font-medium">Conflict!</span> The selected time slot is unavailable for the chosen dentist.
                                </div>
                                <div>
                                    <label for="appointment_type" class="block text-sm font-medium text-gray-700">Appointment Type</label>
                                    <input type="text" name="appointment_type" id="appointment_type" value="Walk-in" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="reason_for_visit" class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                                    <textarea name="reason_for_visit" id="reason_for_visit" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="appointment_notes" class="block text-sm font-medium text-gray-700">Appointment Notes</label>
                                    <textarea name="appointment_notes" id="appointment_notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>

                            <!-- Treatment Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Treatment Details</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Procedures</label>
                                    <div class="mt-2 space-y-2">
                                        @foreach($procedures as $procedure)
                                            <div class="flex items-center">
                                                <input id="procedure_{{ $procedure->id }}" name="procedure_ids[]" type="checkbox" value="{{ $procedure->id }}" data-cost="{{ $procedure->cost }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded procedure-checkbox">
                                                <label for="procedure_{{ $procedure->id }}" class="ml-3 block text-sm font-medium text-gray-700">{{ $procedure->name }} ({{ number_format($procedure->cost, 2) }})</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="teeth" class="block text-sm font-medium text-gray-700">Teeth</label>
                                    <input type="text" name="teeth" id="teeth" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="cost" class="block text-sm font-medium text-gray-700">Total Cost</label>
                                    <input type="number" name="cost" id="cost" step="0.01" readonly class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                </div>
                                <div>
                                    <label for="treatment_notes" class="block text-sm font-medium text-gray-700">Treatment Notes</label>
                                    <textarea name="treatment_notes" id="treatment_notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('patients.show', $patient) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" id="submit-button" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Walk-in Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const costInput = document.getElementById('cost');
            const procedureCheckboxes = document.querySelectorAll('.procedure-checkbox');
            const dentistSelect = document.getElementById('dentist_id');
            const datetimeInput = document.getElementById('appointment_datetime');
            const durationInput = document.getElementById('duration_minutes');
            const conflictMessage = document.getElementById('conflict-message');
            const submitButton = document.getElementById('submit-button');

            function checkConflict() {
                const dentistId = dentistSelect.value;
                const datetime = datetimeInput.value;
                const duration = durationInput.value;

                if (!dentistId || !datetime || !duration) {
                    return;
                }

                fetch('/api/appointments/check-conflict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        dentist_id: dentistId,
                        appointment_datetime: datetime,
                        duration_minutes: duration
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.has_conflict) {
                        conflictMessage.classList.remove('hidden');
                        submitButton.disabled = true;
                    } else {
                        conflictMessage.classList.add('hidden');
                        submitButton.disabled = false;
                    }
                });
            }

            dentistSelect.addEventListener('change', checkConflict);
            datetimeInput.addEventListener('change', checkConflict);
            durationInput.addEventListener('change', checkConflict);

            procedureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    let totalCost = 0;
                    procedureCheckboxes.forEach(cb => {
                        if (cb.checked) {
                            totalCost += parseFloat(cb.dataset.cost);
                        }
                    });
                    costInput.value = totalCost.toFixed(2);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>

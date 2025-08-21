<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Appointment with {{ $appointment->patient->full_name }}</h2>
                            <p class="text-sm text-gray-500">with Dr. {{ $appointment->dentist->name }}</p>
                            <p class="mt-2 px-3 py-1 text-sm font-semibold rounded-full
                                @switch($appointment->status)
                                    @case('scheduled') bg-blue-100 text-blue-800 @break
                                    @case('confirmed') bg-green-100 text-green-800 @break
                                    @case('completed') bg-gray-100 text-gray-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @case('no_show') bg-yellow-100 text-yellow-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if (auth()->user()->hasRole(['administrator', 'receptionist']))
                                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Edit</a>
                            @endif
                            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Back to List</a>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="md:col-span-2 space-y-8">
                            <!-- Appointment Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Appointment Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->appointment_datetime->format('F d, Y') }} at {{ $appointment->appointment_datetime->format('g:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Duration</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->duration_minutes }} minutes</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Appointment Type</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->appointment_type }}</p>
                                    </div>
                                     <div>
                                        <p class="text-sm font-medium text-gray-500">Patient</p>
                                        <p class="text-lg text-gray-900"><a href="{{ route('patients.show', $appointment->patient) }}" class="text-indigo-600 hover:underline">{{ $appointment->patient->full_name }}</a></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Dentist</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->dentist->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Details & Notes</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Reason for Visit</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->reason_for_visit ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Internal Notes</p>
                                        <p class="text-lg text-gray-900">{{ $appointment->appointment_notes ?? 'None' }}</p>
                                    </div>
                                    @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Cancellation Reason</p>
                                        <p class="text-lg text-red-700">{{ $appointment->cancellation_reason }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

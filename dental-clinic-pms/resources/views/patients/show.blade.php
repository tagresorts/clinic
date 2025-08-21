<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20 bg-indigo-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                            </div>
                            <div class="ml-6">
                                <h2 class="text-3xl font-bold text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                                <p class="text-sm text-gray-500">Patient ID: {{ $patient->patient_id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Edit Patient</a>
                            <a href="{{ route('patients.walk-in', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Walk-in</a>
                            @if ($patient->trashed())
                                @can('patient-edit')
                                    <form action="{{ route('patients.restore', $patient->id) }}" method="POST" onsubmit="return confirm('Reactivate this patient?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">Reactivate</button>
                                    </form>
                                @endcan
                            @else
                                @can('patient-delete')
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this patient?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Deactivate</button>
                                    </form>
                                @endcan
                            @endif
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Back to List</a>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="md:col-span-2 space-y-8">
                            <!-- Personal Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Date of Birth</p>
                                        <p class="text-lg text-gray-900">{{ $patient->date_of_birth->format('F d, Y') }} ({{ $patient->age }} years old)</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Gender</p>
                                        <p class="text-lg text-gray-900">{{ ucfirst($patient->gender) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Phone Number</p>
                                        <p class="text-lg text-gray-900">{{ $patient->phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Email Address</p>
                                        <p class="text-lg text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Address</p>
                                        <p class="text-lg text-gray-900">{{ $patient->address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Medical Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Allergies</p>
                                        <p class="text-lg text-gray-900">{{ $patient->allergies ?? 'None recorded' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Medical Conditions</p>
                                        <p class="text-lg text-gray-900">{{ $patient->medical_conditions ?? 'None recorded' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Current Medications</p>
                                        <p class="text-lg text-gray-900">{{ $patient->current_medications ?? 'None recorded' }}</p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('patients.medical-history', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">View Medical History</a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-8">
                            <!-- Emergency Contact Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Emergency Contact</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Name</p>
                                        <p class="text-lg text-gray-900">{{ $patient->emergency_contact_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Phone Number</p>
                                        <p class="text-lg text-gray-900">{{ $patient->emergency_contact_phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Relationship</p>
                                        <p class="text-lg text-gray-900">{{ $patient->emergency_contact_relationship }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Treatment Plan Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">{{ $plan->plan_title }}</h2>
                            <p class="text-sm text-gray-500">For: <a href="{{ route('patients.show', $plan->patient) }}" class="text-indigo-600 hover:underline">{{ $plan->patient->fullName }}</a></p>
                            <p class="text-sm text-gray-500">Assigned Dentist: {{ $plan->dentist->name }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('treatment-plans.edit', $plan) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Edit Plan</a>
                            <a href="{{ route('treatment-plans.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Back to List</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Plan Details Card -->
                        <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Plan Overview</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Diagnosis</p>
                                    <p class="text-lg text-gray-900">{{ $plan->diagnosis }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <p class="text-lg text-gray-900">{{ ucfirst($plan->status) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estimated Cost</p>
                                    <p class="text-lg text-gray-900">₱{{ number_format($plan->estimated_cost, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estimated Duration</p>
                                    <p class="text-lg text-gray-900">{{ $plan->estimated_duration_sessions }} sessions</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">Proposed Procedures</p>
                                    <p class="text-lg text-gray-900">{{ $plan->procedures->pluck('name')->implode(', ') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details Card -->
                        <div class="space-y-8">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Additional Notes</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Treatment Notes</p>
                                        <p class="text-lg text-gray-900">{{ $plan->treatment_notes ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Patient Concerns</p>
                                        <p class="text-lg text-gray-900">{{ $plan->patient_concerns ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Dentist Notes</p>
                                        <p class="text-lg text-gray-900">{{ $plan->dentist_notes ?? 'N/A' }}</p>
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

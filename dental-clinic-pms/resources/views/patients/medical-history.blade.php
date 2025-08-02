<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medical History for ' . $patient->fullName) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Treatment Records</h3>

                    @forelse ($treatmentRecords as $record)
                        <div class="mb-6 p-4 border rounded-lg shadow-sm bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-md font-semibold text-gray-800">Treatment on {{ $record->treatment_date->format('F d, Y') }}</h4>
                                <span class="text-sm text-gray-600">Dentist: {{ $record->dentist->name }}</span>
                            </div>
                            @if ($record->treatmentPlan)
                                <p class="text-gray-700 mb-2"><span class="font-medium">From Plan:</span> <a href="{{ route('treatment-plans.show', $record->treatmentPlan) }}" class="text-indigo-600 hover:underline">{{ $record->treatmentPlan->plan_title }} (ID: {{ $record->treatment_plan_id }})</a></p>
                            @else
                                <p class="text-gray-700 mb-2"><span class="font-medium">From Plan:</span> N/A (ID: {{ $record->treatment_plan_id ?? 'N/A' }})</p>
                            @endif
                            <p class="text-gray-700 mb-2"><span class="font-medium">Procedures:</span> {{ $record->procedures->pluck('name')->implode(', ') }}</p>
                            <p class="text-gray-700"><span class="font-medium">Notes:</span> {{ $record->treatment_notes ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">No treatment records found for this patient.</p>
                    @endforelse

                    <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">All Treatment Plans</h3>

                    @forelse ($treatmentPlans as $plan)
                        <div class="mb-6 p-4 border rounded-lg shadow-sm bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-md font-semibold text-gray-800">{{ $plan->plan_title }} ({{ ucfirst($plan->status) }})</h4>
                                <span class="text-sm text-gray-600">Dentist: {{ $plan->dentist->name }}</span>
                            </div>
                            <p class="text-gray-700 mb-2"><span class="font-medium">Diagnosis:</span> {{ $plan->diagnosis }}</p>
                            <p class="text-gray-700 mb-2"><span class="font-medium">Proposed Procedures:</span> {{ is_array($plan->proposed_procedures) ? implode(', ', $plan->proposed_procedures) : $plan->proposed_procedures }}</p>
                            <p class="text-gray-700"><span class="font-medium">Estimated Cost:</span> ₱{{ number_format($plan->estimated_cost, 2) }}</p>
                            <div class="mt-4">
                                <a href="{{ route('treatment-plans.show', $plan) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">View Plan Details</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No treatment plans found for this patient.</p>
                    @endforelse

                    <div class="mt-6">
                        <x-secondary-button onclick="window.history.back()">
                            {{ __('Back to Patient Details') }}
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Pending Treatment Plans</h3>
        @if($data['pending_treatment_plans']->count() > 0)
            <div class="space-y-3">
                @foreach($data['pending_treatment_plans'] as $plan)
                    <div class="border border-gray-200 rounded p-3">
                        <p class="font-semibold">{{ $plan->patient->full_name }}</p>
                        <p class="text-sm">{{ $plan->plan_title }}</p>
                        <p class="text-xs text-gray-500">${{ number_format($plan->estimated_cost, 2) }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No pending treatment plans.</p>
        @endif
    </div>
</div>

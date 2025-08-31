<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-4 h-full overflow-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Pending Treatment Plans</h3>
        <div class="space-y-2">
            @foreach (($data['pending_treatment_plans'] ?? []) as $plan)
                <div class="flex items-center p-2 text-sm text-gray-700 bg-gray-100 rounded-lg">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                    <div class="flex-1">
                        <div class="font-medium">{{ $plan['patient'] ?? 'Patient' }}</div>
                        <div class="text-xs text-gray-500">{{ $plan['treatment'] ?? 'Treatment' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

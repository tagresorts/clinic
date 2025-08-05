<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold">Financial Summary</h3>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <p class="text-3xl font-bold text-yellow-600">₱{{ number_format($data['revenue_this_month'], 2) }}</p>
                <p class="text-sm text-gray-500">Revenue This Month</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-red-600">₱{{ number_format($data['outstanding_balance'], 2) }}</p>
                <p class="text-sm text-gray-500">Outstanding Balance</p>
            </div>
        </div>
    </div>
</div>

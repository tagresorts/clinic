<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Revenue Reports') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Revenue Reports</h3>
                            <p class="text-sm text-gray-500">Detailed financial analysis and reporting</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('revenue.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Dashboard
                            </a>
                            <a href="{{ route('revenue.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </a>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Date Range Filter</h3>
                        <form method="GET" action="{{ route('revenue.reports') }}" class="flex flex-col sm:flex-row items-end space-y-4 sm:space-y-0 sm:space-x-4">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Revenue Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 rounded-lg border text-center">
                                <p class="text-3xl font-bold text-green-600">${{ number_format($revenue, 2) }}</p>
                                <p class="text-sm text-gray-500">Total Revenue</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $dailyRevenue->count() }}</p>
                                <p class="text-sm text-gray-500">Days with Revenue</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg border text-center">
                                <p class="text-3xl font-bold text-purple-600">${{ $dailyRevenue->count() > 0 ? number_format($revenue / $dailyRevenue->count(), 2) : '0.00' }}</p>
                                <p class="text-sm text-gray-500">Average Daily Revenue</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Top Patients -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Top Patients by Revenue</h3>
                            <div class="space-y-4">
                                @forelse($topPatients as $patient)
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">{{ substr($patient->patient->full_name ?? 'Unknown', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $patient->patient->full_name ?? 'Unknown Patient' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $patient->invoice_count }} invoices</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">${{ number_format($patient->total_revenue, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500">No patient data available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Outstanding Invoices -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Outstanding Invoices</h3>
                            <div class="space-y-4">
                                @forelse($outstandingInvoices->take(10) as $invoice)
                                    <div class="bg-white p-4 rounded-lg border border-red-100">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        #{{ $invoice->invoice_number }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $invoice->patient->full_name ?? 'Unknown' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">${{ number_format($invoice->outstanding_balance, 2) }}</p>
                                                <p class="text-xs text-gray-500">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500">No outstanding invoices</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($outstandingInvoices->count() > 10)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">Showing 10 of {{ $outstandingInvoices->count() }} outstanding invoices</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Daily Revenue Chart -->
                    @if($dailyRevenue->count() > 0)
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md mt-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Daily Revenue Trend</h3>
                            <div class="space-y-3">
                                @foreach($dailyRevenue as $day)
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</span>
                                            <div class="flex items-center space-x-6">
                                                <span class="text-sm text-gray-500">{{ $day->invoice_count }} invoices</span>
                                                <span class="text-sm font-semibold text-gray-900">${{ number_format($day->revenue, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
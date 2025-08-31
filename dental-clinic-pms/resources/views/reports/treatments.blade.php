<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Treatment Reports') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Treatment Reports</h3>
                            <p class="text-sm text-gray-500">Treatment analytics and performance metrics</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Reports
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'treatments', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                        <form method="GET" action="{{ route('reports.treatments') }}" class="flex flex-col sm:flex-row items-end space-y-4 sm:space-y-0 sm:space-x-4">
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

                    <!-- Treatment Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Treatments</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_treatments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Completed</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_treatments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Active</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_treatments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Proposed</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['proposed_treatments'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Treatment Status Breakdown -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Treatment Status Breakdown</h3>
                            <div class="space-y-4">
                                @forelse($statusBreakdown as $status)
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($status->status) }}</span>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($status->count / $stats['total_treatments']) * 100 }}%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900">{{ $status->count }}</span>
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
                                        <p class="text-sm text-gray-500">No treatment data available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Procedure Statistics -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Most Common Procedures</h3>
                            <div class="space-y-4">
                                @forelse($procedureStats->take(8) as $procedure)
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-900">{{ $procedure->name }}</span>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($procedure->count / $procedureStats->max('count')) * 100 }}%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900">{{ $procedure->count }}</span>
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
                                        <p class="text-sm text-gray-500">No procedure data available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Dentist Treatment Performance -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Dentist Treatment Performance</h3>
                            <div class="space-y-4">
                                @forelse($dentistTreatmentPerformance as $dentist)
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">{{ substr($dentist->name ?? 'Unknown', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-900">{{ $dentist->name ?? 'Unknown Dentist' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $dentist->completed_treatments }} completed</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">{{ $dentist->treatment_plans_count }} total</p>
                                                @if($dentist->treatment_plans_count > 0)
                                                    <p class="text-xs text-gray-500">{{ round(($dentist->completed_treatments / $dentist->treatment_plans_count) * 100, 1) }}% completion</p>
                                                @endif
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
                                        <p class="text-sm text-gray-500">No dentist data available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Treatment Completion Rate -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Treatment Completion Rate</h3>
                            <div class="text-center">
                                @if($completionRate && $completionRate->total > 0)
                                    <div class="mb-6">
                                        <div class="relative w-32 h-32 mx-auto">
                                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 32 32">
                                                <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="2" fill="transparent" class="text-gray-200"></circle>
                                                <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="2" fill="transparent" 
                                                    stroke-dasharray="{{ 2 * pi() * 14 }}" 
                                                    stroke-dashoffset="{{ 2 * pi() * 14 * (1 - $completionRate->completion_rate / 100) }}"
                                                    class="text-green-600"></circle>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-2xl font-bold text-gray-900">{{ $completionRate->completion_rate }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="bg-white p-4 rounded-lg border text-center">
                                            <p class="text-sm text-gray-500">Total Treatments</p>
                                            <p class="text-lg font-semibold text-gray-900">{{ $completionRate->total }}</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg border text-center">
                                            <p class="text-sm text-gray-500">Completed</p>
                                            <p class="text-lg font-semibold text-green-600">{{ $completionRate->completed }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500">No completion data available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Insights -->
                    @if($stats['total_treatments'] > 0)
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md mt-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Treatment Insights</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white p-4 rounded-lg border text-center">
                                    <p class="text-2xl font-bold text-green-600">{{ round(($stats['completed_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                    <p class="text-sm text-gray-500">Completion Rate</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg border text-center">
                                    <p class="text-2xl font-bold text-yellow-600">{{ round(($stats['active_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                    <p class="text-sm text-gray-500">Active Rate</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg border text-center">
                                    <p class="text-2xl font-bold text-purple-600">{{ round(($stats['proposed_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                    <p class="text-sm text-gray-500">Proposal Rate</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
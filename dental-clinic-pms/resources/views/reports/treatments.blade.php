<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Treatment Reports') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reports
                </a>
                <a href="{{ route('reports.export', ['type' => 'treatments', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.treatments') }}" class="flex items-center space-x-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Treatment Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Treatments</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_treatments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Completed</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_treatments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_treatments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Proposed</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['proposed_treatments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Treatment Status Breakdown -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Treatment Status Breakdown</h3>
                        <div class="space-y-3">
                            @forelse($statusBreakdown as $status)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">{{ ucfirst($status->status) }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($status->count / $stats['total_treatments']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $status->count }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No treatment data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Procedure Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Most Common Procedures</h3>
                        <div class="space-y-3">
                            @forelse($procedureStats->take(8) as $procedure)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">{{ $procedure->name }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($procedure->count / $procedureStats->max('count')) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $procedure->count }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No procedure data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Dentist Treatment Performance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dentist Treatment Performance</h3>
                        <div class="space-y-3">
                            @forelse($dentistTreatmentPerformance as $dentist)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($dentist->name ?? 'Unknown', 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $dentist->name ?? 'Unknown Dentist' }}</p>
                                            <p class="text-xs text-gray-500">{{ $dentist->completed_treatments }} completed</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $dentist->treatment_plans_count }} total</p>
                                        @if($dentist->treatment_plans_count > 0)
                                            <p class="text-xs text-gray-500">{{ round(($dentist->completed_treatments / $dentist->treatment_plans_count) * 100, 1) }}% completion</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">No dentist data available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Treatment Completion Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Treatment Completion Rate</h3>
                        <div class="text-center">
                            @if($completionRate && $completionRate->total > 0)
                                <div class="mb-4">
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
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Total Treatments</p>
                                        <p class="font-semibold text-gray-900">{{ $completionRate->total }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Completed</p>
                                        <p class="font-semibold text-green-600">{{ $completionRate->completed }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">No completion data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Insights -->
            @if($stats['total_treatments'] > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Treatment Insights</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ round(($stats['completed_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                <p class="text-sm text-gray-500">Completion Rate</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ round(($stats['active_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                <p class="text-sm text-gray-500">Active Rate</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600">{{ round(($stats['proposed_treatments'] / $stats['total_treatments']) * 100, 1) }}%</p>
                                <p class="text-sm text-gray-500">Proposal Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
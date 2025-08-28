<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Viewer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Log File Selection and Stats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Log File Selector -->
                            <div class="flex-1">
                                <label for="log-file" class="block text-sm font-medium text-gray-700 mb-2">Log File</label>
                                <select id="log-file" name="file" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($availableLogFiles as $file)
                                        <option value="{{ $file['name'] }}" {{ $selectedFile == $file['name'] ? 'selected' : '' }}>
                                            {{ $file['name'] }} ({{ $file['size'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Log Level Filter -->
                            <div class="flex-1">
                                <label for="log-level" class="block text-sm font-medium text-gray-700 mb-2">Log Level</label>
                                <select id="log-level" name="level" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="all">All Levels</option>
                                    <option value="emergency" {{ $level == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    <option value="alert" {{ $level == 'alert' ? 'selected' : '' }}>Alert</option>
                                    <option value="critical" {{ $level == 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="error" {{ $level == 'error' ? 'selected' : '' }}>Error</option>
                                    <option value="warning" {{ $level == 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="notice" {{ $level == 'notice' ? 'selected' : '' }}>Notice</option>
                                    <option value="info" {{ $level == 'info' ? 'selected' : '' }}>Info</option>
                                    <option value="debug" {{ $level == 'debug' ? 'selected' : '' }}>Debug</option>
                                </select>
                            </div>

                            <!-- Max Lines -->
                            <div class="flex-1">
                                <label for="max-lines" class="block text-sm font-medium text-gray-700 mb-2">Max Lines</label>
                                <select id="max-lines" name="lines" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="100" {{ $lines == 100 ? 'selected' : '' }}>100</option>
                                    <option value="500" {{ $lines == 500 ? 'selected' : '' }}>500</option>
                                    <option value="1000" {{ $lines == 1000 ? 'selected' : '' }}>1000</option>
                                    <option value="5000" {{ $lines == 5000 ? 'selected' : '' }}>5000</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Box -->
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ $search }}" 
                                       placeholder="Search in log messages..." 
                                       class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <div class="mt-4">
                        <button type="button" onclick="applyFilters()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Log Statistics -->
            @if(isset($logStats))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Log Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">File Size</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $logStats['size'] }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Total Lines</div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($logStats['lines']) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Last Modified</div>
                            <div class="text-2xl font-bold text-gray-900">
                                @if($logStats['modified'])
                                    {{ \Carbon\Carbon::createFromTimestamp($logStats['modified'])->format('M d, H:i') }}
                                @else
                                    Never
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Filtered Results</div>
                            <div class="text-2xl font-bold text-gray-900">{{ count($logs) }}</div>
                        </div>
                    </div>

                    <!-- Log Level Distribution -->
                    @if(!empty($logStats['levels']))
                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Log Level Distribution</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($logStats['levels'] as $level => $count)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $level == 'error' ? 'red' : ($level == 'warning' ? 'yellow' : ($level == 'info' ? 'blue' : 'gray')) }}-100 text-{{ $level == 'error' ? 'red' : ($level == 'warning' ? 'yellow' : ($level == 'info' ? 'blue' : 'gray')) }}-800">
                                    {{ ucfirst($level) }}: {{ number_format($count) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Log Entries -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Log Entries</h3>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('logs.download', $selectedFile) }}" 
                               class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download
                            </a>
                            
                            <form method="POST" action="{{ route('logs.clear', $selectedFile) }}" class="inline" onsubmit="return confirm('Are you sure you want to clear this log file?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('logs.delete', $selectedFile) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this log file? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (isset($error))
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-red-900">Error Loading Logs</h3>
                            <p class="mt-1 text-sm text-red-500">{{ $error }}</p>
                        </div>
                    @elseif (empty($logs))
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No logs found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or select a different log file.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <div class="log-entries space-y-2 max-h-96 overflow-y-auto">
                                @foreach($logs as $log)
                                    <div class="log-entry p-3 rounded-lg border {{ $log['level'] == 'error' ? 'bg-red-50 border-red-200' : ($log['level'] == 'warning' ? 'bg-yellow-50 border-yellow-200' : ($log['level'] == 'info' ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200')) }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $log['level'] == 'error' ? 'red' : ($log['level'] == 'warning' ? 'yellow' : ($log['level'] == 'info' ? 'blue' : 'gray')) }}-100 text-{{ $log['level'] == 'error' ? 'red' : ($log['level'] == 'warning' ? 'yellow' : ($log['level'] == 'info' ? 'blue' : 'gray')) }}-800">
                                                        {{ ucfirst($log['level']) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $log['timestamp'] }}</span>
                                                    @if($log['environment'] !== 'unknown')
                                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ $log['environment'] }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-900 font-mono break-words">{{ $log['message'] }}</div>
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

    <script>
        function applyFilters() {
            const file = document.getElementById('log-file').value;
            const level = document.getElementById('log-level').value;
            const search = document.getElementById('search').value;
            const lines = document.getElementById('max-lines').value;
            
            const params = new URLSearchParams({
                file: file,
                level: level,
                search: search,
                lines: lines
            });
            
            window.location.href = '{{ route("logs.index") }}?' + params.toString();
        }

        // Auto-refresh every 30 seconds
        setInterval(function() {
            // Only auto-refresh if no filters are applied
            if (document.getElementById('search').value === '' && document.getElementById('log-level').value === 'all') {
                applyFilters();
            }
        }, 30000);
    </script>
</x-app-layout>

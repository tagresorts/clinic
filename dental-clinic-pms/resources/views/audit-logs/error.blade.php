<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs Error') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-50 border border-red-200 rounded-md p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Error Loading Audit Logs
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p><strong>Error:</strong> {{ $error }}</p>
                            
                            @if(config('app.debug'))
                                <div class="mt-4">
                                    <details class="text-xs">
                                        <summary class="cursor-pointer font-medium">Stack Trace</summary>
                                        <pre class="mt-2 bg-red-100 p-2 rounded overflow-auto">{{ $trace }}</pre>
                                    </details>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('audit-logs.debug') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        Debug Info
                    </a>
                    <a href="{{ route('dashboard') }}" class="ml-3 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
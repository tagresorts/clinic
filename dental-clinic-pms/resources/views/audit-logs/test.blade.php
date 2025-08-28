<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Test</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            ✅ Layout is working!
                        </div>
                        
                        <div>
                            <strong>Current Route:</strong> {{ request()->route()->getName() }}
                        </div>
                        
                        <div>
                            <strong>User:</strong> {{ auth()->user() ? auth()->user()->name : 'Not authenticated' }}
                        </div>
                        
                        <div>
                            <strong>Time:</strong> {{ now() }}
                        </div>
                    </div>
                    
                    <div class="mt-6 space-x-3">
                        <a href="{{ route('audit-logs.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Try Full View
                        </a>
                        <a href="{{ route('audit-logs.debug') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Debug Info
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
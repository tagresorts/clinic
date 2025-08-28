<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs Debug') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Debug Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <strong>Route:</strong> {{ request()->route()->getName() }}
                        </div>
                        
                        <div>
                            <strong>Controller:</strong> {{ request()->route()->getController() ? get_class(request()->route()->getController()) : 'None' }}
                        </div>
                        
                        <div>
                            <strong>User:</strong> {{ auth()->user() ? auth()->user()->name : 'Not authenticated' }}
                        </div>
                        
                        <div>
                            <strong>User Role:</strong> {{ auth()->user() ? auth()->user()->getRoleNames()->first() : 'No role' }}
                        </div>
                        
                        <div>
                            <strong>Session ID:</strong> {{ session()->getId() }}
                        </div>
                        
                        <div>
                            <strong>Current Time:</strong> {{ now() }}
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('audit-logs.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Back to Full View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
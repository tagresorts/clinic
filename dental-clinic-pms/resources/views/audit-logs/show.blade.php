<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log Details') }}
        </h2>
    </x-slot>
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('audit-logs.index') }}" class="text-blue-600 hover:text-blue-900 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Audit Logs
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Audit Log Entry #{{ $auditLog->id }}</h1>
                    <p class="text-gray-600 mt-1">{{ $auditLog->description }}</p>
                </div>
                <div class="flex space-x-3">
                    @if($auditLog->requires_review)
                        <form method="POST" action="{{ route('audit-logs.review', $auditLog) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                Mark Reviewed
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('audit-logs.mark-review', $auditLog) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm">
                                Mark for Review
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Action</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($auditLog->action === 'created') bg-green-100 text-green-800
                                    @elseif($auditLog->action === 'updated') bg-blue-100 text-blue-800
                                    @elseif($auditLog->action === 'deleted') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($auditLog->action) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Severity</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($auditLog->severity === 'high') bg-red-100 text-red-800
                                    @elseif($auditLog->severity === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($auditLog->severity) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Time</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->event_time->format('F d, Y \a\t g:i A') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Requires Review</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($auditLog->requires_review) bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $auditLog->requires_review ? 'Yes' : 'No' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->user_name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Role</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->user_role }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">User ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->user_id ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Session ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $auditLog->session_id }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Entity Information -->
            @if($auditLog->entity_type)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Entity Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Entity Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->entity_type }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Entity ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->entity_id ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Entity Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->entity_description ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
            @endif

            <!-- Request Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Request Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $auditLog->ip_address ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Request Method</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->request_method ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Request URL</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $auditLog->request_url ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $auditLog->user_agent ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Changed Fields -->
            @if($auditLog->changed_fields && count($auditLog->changed_fields) > 0)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Changed Fields</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($auditLog->changed_fields as $field)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $field }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Values Comparison -->
            @if($auditLog->old_values || $auditLog->new_values)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Values Comparison</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if($auditLog->old_values)
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Previous Values</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif

                    @if($auditLog->new_values)
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">New Values</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Metadata -->
            @if($auditLog->metadata)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Metadata</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-900">{{ $auditLog->description }}</p>
                </div>
            </div>
        </div>
            </div>
    </div>
</x-app-layout>
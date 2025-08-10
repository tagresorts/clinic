<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Email Template') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success') || session('error'))
                        <div class="mb-4">
                            @if (session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                    <form method="POST" action="{{ route('email-templates.update', $emailTemplate) }}">
                        @csrf
                        @method('PUT')
                        @include('email_templates._form')
                    </form>
                    <hr class="my-6"/>
                    <form method="POST" action="{{ route('email-templates.test', $emailTemplate) }}" class="flex items-end space-x-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Send test to</label>
                            <input type="email" name="to" class="mt-1 block w-72 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="you@example.com" required />
                        </div>
                        <x-secondary-button type="submit">Send Test</x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

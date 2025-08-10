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
                        <x-secondary-button>Send Test</x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

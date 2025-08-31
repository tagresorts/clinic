<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v8.25A2.25 2.25 0 006 16.5h2.25m.75-2.25h7.5m-7.5 0l-2.25 2.25M16.5 10.5L18 12m0 0l2.25 2.25M16.5 10.5V18a2.25 2.25 0 002.25 2.25H21" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Widget Border Style') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.dashboard-borders.update') }}">
                        @csrf
                        <div>
                            <label for="border_style" class="block font-medium text-sm text-gray-700">{{ __('Border Style') }}</label>
                            <select id="border_style" name="border_style" class="block mt-1 w-full">
                                @foreach ($borderStyles as $style)
                                    <option value="{{ $style }}" {{ $borderStyle == $style ? 'selected' : '' }}>{{ ucfirst($style) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

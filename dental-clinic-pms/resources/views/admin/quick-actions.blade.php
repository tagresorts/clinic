<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quick Actions Settings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.quick-actions.update') }}" class="space-y-3">
                        @csrf
                        @foreach ($actions as $a)
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="qa_{{ $a['id'] }}" @checked($a['enabled']) class="rounded border-gray-300">
                                <span class="text-sm text-gray-800">{{ $a['label'] }}</span>
                            </label>
                        @endforeach
                        <div class="pt-4">
                            <x-primary-button type="submit">Save</x-primary-button>
                            <a href="{{ route('dashboard') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


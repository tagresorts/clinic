<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-200">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Search</h3>
        <form action="{{ route('patients.index') }}" method="GET">
            <div class="flex items-center">
                <x-text-input id="search" name="search" class="block w-full" placeholder="Search patients..." />
                <x-primary-button class="ml-2">Search</x-primary-button>
            </div>
        </form>
    </div>
</div>

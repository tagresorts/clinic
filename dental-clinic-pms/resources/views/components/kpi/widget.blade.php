@props(['title', 'value', 'icon'])

<div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
    <div class="bg-blue-500 text-white rounded-full p-3">
        <i class="fas {{ $icon }}"></i>
    </div>
    <div class="ml-4">
        <p class="text-gray-500 text-sm">{{ $title }}</p>
        <p class="text-2xl font-bold">{{ $value }}</p>
    </div>
</div>

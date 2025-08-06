@props(['title', 'value', 'icon'])

<div class="bg-blue-500 p-6 rounded-lg shadow-lg text-white">
    <div class="flex items-center mb-2">
        <i class="fas {{ $icon }} fa-lg mr-3"></i>
        <h4 class="font-semibold">{{ $title }}</h4>
    </div>
    <p class="text-4xl font-bold">{{ is_numeric($value) ? number_format($value, 0) : $value }}</p>
</div>
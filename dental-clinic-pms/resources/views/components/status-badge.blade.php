@props(['status'])

@php
$colorClasses = [
    'proposed' => 'bg-yellow-100 text-yellow-800',
    'patient_approved' => 'bg-blue-100 text-blue-800',
    'in-progress' => 'bg-indigo-100 text-indigo-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-red-100 text-red-800',
];

$classes = $colorClasses[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $classes]) }}>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
@php
$logo = \App\Models\Setting::where('key','clinic_logo_url')->value('value');
@endphp
<img src="{{ $logo ?: asset('logo.png') }}" alt="Application Logo" {{ $attributes }}>

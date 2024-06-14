@props([
    'color' => 'primary', // default color
    'icon' => null, // optional icon
])

@php
    $gradientClasses = \App\Utils\UiUtils::getGradientClasses($color);
@endphp

<button
    {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r $gradientClasses focus:outline-none focus:ring-2 focus:ring-offset-2"]) }}>
    @if ($icon)
        <i class="fas fa-{{ $icon }} mr-2"></i>
    @endif
    {{ $slot }}
</button>

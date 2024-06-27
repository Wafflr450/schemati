@props([
    'color' => 'none', // default color
    'icon' => null, // optional icon
    'textColor' => 'text-white', // default text color
])

@php
    $gradientClasses = \App\Utils\UiUtils::getGradientClasses($color);
@endphp

<button
    {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-1 border border-transparent text-sm font-medium rounded-lg shadow-md  $gradientClasses transition duration-150 ease-in-out transform hover:scale-[1.05] active:scale-[0.99] $textColor"]) }}>
    @if ($icon)
        <i class="fas fa-{{ $icon }}"></i>
    @endif
    @if (!$slot->isEmpty())
        <span class="ml-2">{{ $slot }}</span>
    @endif
</button>

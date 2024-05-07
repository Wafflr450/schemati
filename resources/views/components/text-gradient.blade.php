<!-- resources/views/components/text-gradient.blade.php -->

@props(['class' => '', 'color' => 'primary'])

<span
    {{ $attributes->merge(['class' => 'bg-gradient-to-r ' . \App\Utils\UiUtils::getGradientClasses($color) . ' bg-clip-text text-transparent ' . $class]) }}>
    {{ $slot }}
</span>

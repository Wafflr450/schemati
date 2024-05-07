@props([
    'icon' => 'fas fa-share',
    'title' => '',
    'description' => '',
    'backgroundColor' => 'neutral',
    'iconColor' => 'primary',
])

<div
    {{ $attributes->merge(['class' => 'flex flex-col h-full px-6 pb-8 rounded-lg shadow-lg  bg-gradient-to-tl ' . UiUtils::getGradientClasses($backgroundColor)]) }}>
    <div class="flex-shrink-0 flex items-center justify-center -mt-6">
        <x-gradienticon :icon="$icon" class="w-12 h-12" color="{{ $iconColor }}" />
    </div>
    <div class="flex-grow mt-6">
        <h3 class="text-lg font-medium tracking-tight text-white">{{ $title }}</h3>
        <p class="mt-4 text-base text-gray-300">{{ $description }}</p>
    </div>
</div>

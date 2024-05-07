<!-- resources/views/components/schematic-tools-card.blade.php -->

@props([
    'title' => '',
    'backgroundColor' => 'neutral',
    'list' => [],
])

<div
    {{ $attributes->merge(['class' => 'bg-gradient-to-tl ' . UiUtils::getGradientClasses($backgroundColor) . ' shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full']) }}>
    <h2 class="text-2xl font-semibold text-white mb-4">{{ $title }}</h2>
    <div class="space-y-4">
        @foreach ($list as $item)
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <x-gradienticon :icon="$item['icon']" class="w-12 h-12" color="{{ $item['iconColor'] }}" />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-white">{{ $item['title'] }}</h3>
                    <p class="mt-1 text-gray-300">{{ $item['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

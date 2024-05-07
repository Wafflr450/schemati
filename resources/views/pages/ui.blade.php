<x-app-layout>
    @php
        $colors = ['primary', 'secondary', 'accent', 'neutral', 'base', 'info', 'success', 'warning', 'error'];
    @endphp



    @foreach ($colors as $color)
        <h1 class="text-4xl font-bold mb-4 text-center">
            Text <x-text-gradient color="{{ $color }}">Gradient</x-text-gradient>
        </h1>
    @endforeach

    <div class="container mx-auto py-8">
        <x-color-palette :colors="$colors" />
    </div>


    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-4 text-center">Icon Showcase</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            @foreach ($colors as $color)
                <div class="flex justify-center">
                    <x-gradienticon icon="fas fa-star" color="{{ $color }}" class="w-16 h-16" />
                </div>
            @endforeach
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-4 text-center">Text Styles</h2>
        <div class="space-y-4">
            <p class="text-4xl font-bold">This is a text-4xl font-bold.</p>
            <p class="text-3xl font-semibold">This is a text-3xl font-semibold.</p>
            <p class="text-2xl font-medium">This is a text-2xl font-medium.</p>
            <p class="text-xl font-normal">This is a text-xl font-normal.</p>
            <p class="text-lg font-light">This is a text-lg font-light.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-4 text-center">Card Showcase</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach (range(1, 6) as $card)
                <x-card>
                    <x-slot name="header">
                        <h2 class="text-xl font-semibold text-white">Card Header</h2>
                    </x-slot>
                    <x-slot name="body">
                        <p class="text-gray-700">This is the card body content.</p>
                        <div class="mt-4">
                            <x-button.primary>Primary Action</x-button.primary>
                            <x-button.secondary>Secondary Action</x-button.secondary>
                        </div>
                    </x-slot>
                    <x-slot name="footer">
                        <p class="text-sm text-gray-600">Card footer content goes here.</p>
                    </x-slot>
                </x-card>
            @endforeach
        </div>
    </div>

</x-app-layout>

@props([
    'colors' => [],
    'shades' => [
        '100' => '100',
        '200' => '200',
        '300' => '300',
        '400' => '400',
        '500' => '500',
        '600' => '600',
        '700' => '700',
        '800' => '800',
        '900' => '900',
    ],
])
<h2 class="text-3xl font-bold mb-4">Color Palette</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pb-8">
    @foreach ($colors as $name)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6 bg-{{ $name }}-500">
                <h3 class="text-lg font-medium text-white">{{ ucfirst($name) }}</h3>
            </div>
            <div class="px-4 py-5 sm:p-6 grid grid-cols-5 gap-2">
                @foreach ($shades as $shade => $value)
                    <div>
                        <div class="h-12 w-full rounded bg-{{ $name }}-{{ $shade }}"></div>
                        <div class="px-2 py-1 text-sm font-semibold text-gray-700">
                            {{ $shade }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
<h2 class="text-3xl font-bold mb-4">Gradient Palette</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach ($colors as $name)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6 bg-gradient-to-r {{ \App\Utils\UiUtils::getGradientClasses($name) }}">
                <h3 class="text-lg font-medium text-white">{{ ucfirst($name) }}</h3>
            </div>
        </div>
    @endforeach
</div>

<!-- resources/views/components/button/cta.blade.php -->

@props([
    'href' => '',
    'color' => 'primary',
    'content' => '',
])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' =>
            ' bg-gradient-to-r  relative inline-flex items-center justify-center px-8 py-3 text-lg font-semibold text-white rounded-full shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 active:translate-y-0 active:scale-100 ' .
            UiUtils::getGradientClasses($color),
    ]) }}>
    <span class="relative z-10">{{ $content }}</span>
    <span
        class="absolute inset-0 bg-gradient-to-r from-transparent to-white opacity-20 animate-pulse rounded-full"></span>
    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
            clip-rule="evenodd"></path>
    </svg>
</a>

@push('styles')
    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 0.3;
            }
        }
    </style>
@endpush

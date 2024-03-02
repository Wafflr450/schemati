@php
    $uuid = Str::uuid();
@endphp

<a href="{{ $href }}" wire:navigate.hover id="hero-cta-{{ $uuid }}"
    class="focus:ring-pink-300 focus:ring-pink-800  relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 active:bg-pink-600 active:text-white transition-all ease-in duration-75 active:scale-95">
    <span
        class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-base-100 rounded-md group-hover:bg-opacity-0 flex items-center justify-center">
        {{ $content }}
        <i id="animated-arrow-{{ $uuid }}" class="fas fa-arrow-right pl-2"></i>
    </span>
</a>

@push('scripts')
    <script>
        //make the arrow animate on hover
        document.getElementById('hero-cta-{{ $uuid }}').addEventListener('mouseenter', function() {
            gsap.to("#animated-arrow-{{ $uuid }}", {
                x: 10,
                duration: 0.5,
                ease: "back",
            });
        });
        document.getElementById('hero-cta-{{ $uuid }}').addEventListener('mouseleave', function() {
            gsap.to("#animated-arrow-{{ $uuid }}", {
                x: 0,
                duration: 0.5,
                ease: "back",
            });
        });
    </script>
@endpush

<img src="{{ asset('img/logo.png') }}" alt="Logo" {{ $attributes }} id="hero-animated-logo" />

@push('scripts')
    <script>
        //gsap animation on initial load
        gsap.fromTo("#hero-animated-logo", {
            scale: 0,
            opacity: 0
        }, {
            scale: 1,
            opacity: 1,
            duration: 1,
            ease: "back"
        });
    </script>
@endpush

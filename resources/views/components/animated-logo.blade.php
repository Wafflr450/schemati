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

        {{--  document.getElementById('hero-animated-logo').addEventListener('mouseenter', function() {
            gsap.to("#hero-animated-logo", {
                rotation: '+=360',
                duration: 1,
                ease: "back",
                transformOrigin: "50% 60%"
            });
        });  --}}
    </script>
@endpush

<x-app-layout>


    <section class="bg-base-100">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
            {{--  <x-animated-logo class="w-auto h-48 mx-auto mb-8" />  --}}
            <div class="flex items-center justify-center mb-8">
                <x-animated-logo-svg class="flex items-center justify-center w-32  rounded-full bg-primary-500" />
            </div>
            <h1 id="hero-animated-h1"
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white text-shadow-lg">
                Welcome to schem.at
            </h1>
            <p id="hero-animated-subtitle"
                class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Here at
                schem.at, we provide a platform for sharing and discovering Minecraft schematics. Whether you're looking
                for a new build to add to your world, or you're looking to share your own creations, schem.at is the
                place for you.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0" id="view-schematics-cta">
                <x-inputs.buttons.cta href="/schematics" content="View Schematics" />
            </div>

            {{--  <x-tag-tree />  --}}

        </div>
    </section>

    @push('scripts')
        <script>
            gsap.timeline()
                .fromTo("#hero-animated-h1", {
                    opacity: 0,
                    y: 20
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "back"
                })
                .fromTo("#hero-animated-subtitle", {
                    opacity: 0,
                    y: 20
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "back"
                }, "-=0.5")
                .fromTo("#view-schematics-cta", {
                    opacity: 0,
                    y: 20
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "back"
                }, "-=0.5");
        </script>
    @endpush
</x-app-layout>

<x-app-layout>


    <section class="bg-base-100">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
            <x-animated-logo class="w-auto h-48 mx-auto mb-8" />
            <h1 id="hero-animated-h1"
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                Welcome to schem.at
            </h1>
            <p id="hero-animated-subtitle"
                class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Here at
                schem.at, we provide a platform for sharing and discovering Minecraft schematics. Whether you're looking
                for a new build to add to your world, or you're looking to share your own creations, schem.at is the
                place for you.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="/schematics" wire:navigate id="hero-cta"
                    class="focus:ring-pink-300 focus:ring-pink-800 shadow-lg shadow-pink-500/50 relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800">
                    <span
                        class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-base-100 rounded-md group-hover:bg-opacity-0">
                        View Schematics <i class="fas fa-arrow-right ms-2"></i>
                    </span>
                </a>
            </div>


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
                }, "-=0.5");
            gsap.fromTo("#hero-cta", {
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
</x-app-layout>

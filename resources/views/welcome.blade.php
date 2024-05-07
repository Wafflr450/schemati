<x-app-layout>


    <section class="">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
            {{--  <x-animated-logo class="w-auto h-48 mx-auto mb-8" />  --}}
            {{--  <x-tag-tree />  --}}
            <div class="flex items-center justify-center mb-8">
                <x-animated-logo-svg class="flex items-center justify-center w-32  rounded-full bg-primary-500" />
            </div>
            <h1 id="hero-animated-h1"
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white text-shadow-lg">
                Welcome to schemat.<span
                    class="bg-gradient-to-r from-fuchsia-600 to-pink-500 bg-clip-text text-transparent">io</span>
            </h1>
            <p id="hero-animated-subtitle"
                class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">
                A community-driven platform for sharing and discovering Minecraft schematics.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0" id="view-schematics-cta">
                <x-inputs.buttons.cta href="/schematics" content="View Schematics" />
            </div>



        </div>

        <div class="absolute top-0 z-[-2] h-screen w-screen"></div>

        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="pt-6">
                    <div
                        class="flex flex-col h-full px-6 pb-8 rounded-lg bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_10%_-20%,rgba(120,119,120,0.3),rgba(120,120,120,0.1))] shadow-lg">
                        <div class="flex-shrink-0 flex items-center justify-center -mt-6">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-fuchsia-600 to-pink-500 rounded-full">
                                <i class="w-4 h-5 text-white fas fa-share"></i>
                            </div>
                        </div>
                        <div class="flex-grow mt-6">
                            <h3 class="text-lg font-medium tracking-tight text-white">Share Your Creations</h3>
                            <p class="mt-4 text-base text-gray-200">
                                Showcase your own Minecraft builds and schematics. Upload your creations, share them
                                with the community, and get valuable feedback from fellow builders.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div
                        class="flex flex-col h-full px-6 pb-8 rounded-lg bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_10%_-20%,rgba(120,119,120,0.3),rgba(120,120,120,0.1))] shadow-lg">

                        <div class="flex-shrink-0 flex items-center justify-center -mt-6">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-fuchsia-600 to-pink-500 rounded-full">
                                <i class="w-4 h-5 text-white fas fa-compass"></i>
                            </div>
                        </div>
                        <div class="flex-grow mt-6">
                            <h3 class="text-lg font-medium tracking-tight text-white">Discover New Builds</h3>
                            <p class="mt-4 text-base text-gray-200">
                                Explore a vast collection of Minecraft schematics created by talented builders from
                                around the world. Find inspiration for your own builds and enhance your Minecraft
                                experience.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div
                        class="flex flex-col h-full px-6 pb-8 rounded-lg bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_10%_-20%,rgba(120,119,120,0.3),rgba(120,120,120,0.1))] shadow-lg">

                        <div class="flex-shrink-0 flex items-center justify-center -mt-6">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-fuchsia-600 to-pink-500 rounded-full">
                                <i class="w-5 h-5 text-white fas fa-users"></i>
                            </div>
                        </div>
                        <div class="flex-grow mt-6">
                            <h3 class="text-lg font-medium tracking-tight text-white">Join the Community</h3>
                            <p class="mt-4 text-base text-gray-200">
                                Connect with a passionate community of Minecraft enthusiasts. Collaborate, share ideas,
                                and engage with builders from around the world who share your love for the game.
                            </p>
                        </div>
                    </div>
                </div>
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

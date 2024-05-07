<x-app-layout>


    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
        {{--  <x-animated-logo class="w-auto h-48 mx-auto mb-8" />  --}}
        {{--  <x-tag-tree />  --}}
        <div class="flex items-center justify-center mb-8">
            <x-animated-logo-svg class="flex items-center justify-center w-32" />
        </div>
        <h1 id="hero-animated-h1"
            class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-shadow-lg">
            Welcome to schemat.<x-text-gradient color="primary">io</x-text-gradient>
        </h1>


        <p id="hero-animated-subtitle"
            class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">
            A community-driven platform for sharing and discovering Minecraft schematics.
        </p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0" id="view-schematics-cta">
            <x-inputs.buttons.cta href="/schematics" content="View Schematics" color="primary" />
        </div>
    </div>
    <section class="">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-feature-card icon="fas fa-share" title="Share Your Creations"
                    description="Showcase your own Minecraft builds and schematics. Upload your creations, share them with the community, and get valuable feedback from fellow builders." />
                <x-feature-card icon="fas fa-compass" title="Discover New Builds"
                    description="Explore a vast collection of Minecraft schematics created by talented builders from around the world. Find inspiration for your own builds and enhance your Minecraft experience." />
                <x-feature-card icon="fas fa-users" title="Join the Community"
                    description="Connect with a passionate community of Minecraft enthusiasts. Collaborate, share ideas, and engage with builders from around the world who share your love for the game." />
            </div>
        </div>

    </section>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex justify-center items-center">
                    <div
                        class="relative bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_10%_-20%,rgba(120,119,120,0.3),rgba(120,120,120,0.1))] shadow-lg rounded-lg shadow-lg">
                        <video autoplay loop muted playsinline class=" rounded-lg shadow-lg">
                            <source
                                src="https://schemat-io.s3.us-east-005.backblazeb2.com/schemat-io/big_piston_25.webm"
                                type="video/webm">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <x-list-card title="Advanced Schematic Tools" :list="[
                    [
                        'icon' => 'fas fa-cubes',
                        'iconColor' => 'primary',
                        'title' => '3D Schematic Viewer',
                        'description' =>
                            'Visualize your schematics in stunning 3D with our interactive viewer. Rotate, zoom, and explore every detail of your creations.',
                    ],
                    [
                        'icon' => 'fas fa-edit',
                        'iconColor' => 'primary',
                        'title' => 'In-Browser Editing',
                        'description' =>
                            'Make changes to your schematics directly in your browser. Add, remove, or modify blocks with ease using our intuitive editing tools.',
                    ],
                    [
                        'icon' => 'fas fa-share-alt',
                        'iconColor' => 'primary',
                        'title' => 'One-Click Sharing',
                        'description' =>
                            'Share your schematics with the world in just one click. Generate shareable links or embed your creations on your own website.',
                    ],
                ]" />
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

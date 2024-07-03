<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    {{--  <script defer src="http://localhost:3000/bundle.js" wire:ignore></script>  --}}

    {{--  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/echo.js'])  --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <!-- Styles -->
    @filamentStyles
    @livewireStyles
</head>

<body class="font-sans antialiased dark">
    <div
        class="fixed top-0 z-[-1] h-full w-full bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.3),rgba(255,255,255,0))]">
        <div
            class="fixed bottom-0 left-0 right-0 top-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_14px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)]">
        </div>
    </div>

    <x-login-modal />
    <x-register-modal />
    <livewire:toasts />

    <x-banner />


    <div class="min-h-screen">
        @livewire('navigation-menu')
        <!-- Page Heading -->
        @if (isset($header))
            <header class=" shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>


    @stack('modals')





    @once

        <script src="https://kit.fontawesome.com/3287ce58d5.js" crossorigin="anonymous"></script>

        <script>
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.scroll-animation');

                elements.forEach((element) => {
                    const position = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    const threshold = element.getAttribute('data-threshold');

                    if (position < windowHeight * threshold) {
                        element.classList.add('animate');
                    } else {
                        element.classList.remove('animate');
                    }
                });
            };

            window.addEventListener('scroll', animateOnScroll);
            window.addEventListener('load', animateOnScroll);
        </script>
        {{--  <script type='module'>
        Echo.join('reverb')
            .listen('ToastEvent', (e) => {
                console.log(e.message.type, e.message.message, e.message.title);
                Toast[e.message.type](e.message.message, e.message.title);
            });
    </script>  --}}
        <script>
            async function getRessourcePackLinks() {
                const vanillaPack = "https://www.curseforge.com/api/v1/mods/457153/files/5008188/download";

                const packs = [
                    `/api/fetch-resource-pack?url=${encodeURIComponent(vanillaPack)}`
                ];

                return packs;
            }

            async function getAllResourcePackBlobs() {
                const resourcePackBlobs = [];
                const ressourcePackLinks = await getRessourcePackLinks();
                for (const resourcePackLink of ressourcePackLinks) {
                    const response = await fetch(resourcePackLink);
                    const resourcePackBlob = await response.blob();
                    resourcePackBlobs.push(resourcePackBlob);
                }
                return resourcePackBlobs;
            }
        </script>
        <script>
            function copyToClipboard(text) {
                const input = document.createElement("input");
                input.value = text;
                document.body.appendChild(input);
                input.select();
                document.execCommand("copy");
                document.body.removeChild(input);
                Toast.success("Copied to clipboard!");
            }
        </script>
        <script>
            @if (session('error'))
                setTimeout(() => {
                    Toast.danger("{{ session('error') }}");
                }, 100);
            @endif
            @if (session('success'))
                setTimeout(() => {
                    Toast.success("{{ session('success') }}");
                }, 100);
            @endif
        </script>
        <script>
            const konamiCode = [
                "ArrowUp",
                "ArrowUp",
                "ArrowDown",
                "ArrowDown",
                "ArrowLeft",
                "ArrowRight",
                "ArrowLeft",
                "ArrowRight",
                "b",
                "a",
            ];
            let konamiCodePosition = 0;
            document.addEventListener("keydown", function(e) {
                if (e.key === konamiCode[konamiCodePosition]) {
                    konamiCodePosition++;
                    Toast.info(e.key);
                    if (konamiCodePosition === konamiCode.length) {
                        konamiCodePosition = 0;
                        Toast.success("Konami Code activated!");
                        setTimeout(() => {
                            window.location.href = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
                        }, 1000);
                    }
                } else {
                    konamiCodePosition = 0;
                }
            });
        </script>
    @endonce
    @stack('scripts')
    @filamentScripts
    @livewireScripts
</body>

</html>

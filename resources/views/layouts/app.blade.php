<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/echo.js'])
    <script src="/js/bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <!-- Styles -->
    @filamentStyles
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div
        class="fixed top-0 z-[-1] h-full w-full bg-neutral-950 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.3),rgba(255,255,255,0))]">
        <div
            class="fixed bottom-0 left-0 right-0 top-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_14px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)]">
        </div>
    </div>

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






    <script src="https://kit.fontawesome.com/3287ce58d5.js" crossorigin="anonymous"></script>
    <script type='module'>
        Echo.join('reverb')
            .listen('ToastEvent', (e) => {
                console.log(e.message.type, e.message.message, e.message.title);
                Toast[e.message.type](e.message.message, e.message.title);
            });
    </script>
    <script>
        async function openDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open("minecraftDB", 1);
                request.onupgradeneeded = function(event) {
                    const db = event?.target?.result;
                    if (!db.objectStoreNames.contains("jars")) {
                        db.createObjectStore("jars");
                    }
                };
                request.onsuccess = function(event) {
                    resolve(event?.target?.result);
                };
                request.onerror = function(event) {
                    reject("Error opening IndexedDB.");
                };
            });
        }

        function base64ToUint8Array(base64) {
            const binaryString = atob(base64);
            const len = binaryString.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes;
        }

        async function getCachedMinecraftJarUrl() {
            const jarURL = "/jars/client.jar";
            const jarUrlHash = "c0898ec7c6a5a2eaa317770203a1554260699994";
            const db = await openDatabase();
            const transaction = db.transaction(["jars"], "readonly");
            const objectStore = transaction.objectStore("jars");
            const request = objectStore.get(jarUrlHash);
            return new Promise(async (resolve, reject) => {
                request.onsuccess = function(event) {
                    if (request.result) {
                        console.log("Jar found in IndexedDB.");
                        resolve(URL.createObjectURL(request.result));
                    } else {
                        console.log(
                            "Jar not found in IndexedDB, fetching from Mojang..."
                        );
                        fetch(jarURL)
                            .then((response) => {
                                if (!response.ok) {
                                    throw new Error("HTTP error " + response.status);
                                }
                                console.log("Jar fetched from Mojang, unzipping...");
                                const blob = response.blob();
                                console.log(blob);
                                return blob;
                            })
                            .then((blob) => {
                                console.log(
                                    "Jar fetched from Mojang, storing in IndexedDB..."
                                );
                                return blob;
                            })
                            .then((blob) => {
                                const addRequest = db
                                    .transaction(["jars"], "readwrite")
                                    .objectStore("jars")
                                    .add(blob, jarUrlHash);

                                addRequest.onsuccess = function(event) {
                                    resolve(URL.createObjectURL(blob));
                                };
                                addRequest.onerror = function(event) {
                                    reject("Error storing jar in IndexedDB.");
                                };
                            })
                            .catch((error) => {
                                reject("Error fetching jar from Mojang.");
                            });
                    }
                };
                request.onerror = function(event) {
                    reject("Error fetching jar from IndexedDB.");
                };
            });
        }
        const defaultSchematicOptions = {
            getClientJarUrl: async (props) => {
                return await getCachedMinecraftJarUrl();
            },
        };
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
    @stack('scripts')
    @filamentScripts
    @livewireScripts
</body>

</html>

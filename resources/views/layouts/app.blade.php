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

    {{--  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/echo.js'])  --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            async function getVanillaTweaksResourcePackLinks() {
                return fetch(
                        "http://localhost:8079/https://vanillatweaks.net/assets/server/zipresourcepacks.php", {
                            method: "POST",
                            headers: {
                                "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
                            },
                            body: "packs=%7B%22aesthetic.more-zombies%22%3A%5B%22MZSteve%22%2C%22MZAlex%22%2C%22MZAri%22%2C%22MZEfe%22%2C%22MZKai%22%2C%22MZMakena%22%2C%22MZNoor%22%2C%22MZSunny%22%2C%22MZZuri%22%5D%2C%22aesthetic%22%3A%5B%22AlternateBlockDestruction%22%2C%22BlackNetherBricks%22%2C%22CherryPicking%22%2C%22UnbundledHayBales%22%2C%22UnbundledDriedKelp%22%2C%22SolidHoney%22%2C%22SolidSlime%22%2C%22WarmGlow%22%2C%22LessPurplePurpur%22%2C%22DarkOakSaturation%22%2C%22HorizontalNuggets%22%2C%22SidewaysNuggets%22%2C%22SofterWool%22%2C%22BrownLeather%22%2C%22RedIronGolemFlowers%22%2C%22StemToLog%22%2C%22BetterParticles%22%2C%22HDShieldBanners%22%2C%22EndlessEndRods%22%2C%22PinkEndRods%22%2C%22UniqueDyes%22%2C%22AnimatedCampfireItem%22%2C%22AshlessCampfires%22%2C%22GlassDoors%22%2C%22GlassTrapdoors%22%2C%22FlintTippedArrows%22%2C%22SplashXpBottle%22%2C%222DSpyglass%22%2C%22AccurateSpyglass%22%2C%22AccurateScaffolding%22%2C%22FencierFences%22%2C%22MossCarpetOverhang%22%2C%22SmootherWarpedPlanks%22%2C%22ConsistentBambooPlanks%22%2C%22AlternateCutCopper%22%2C%22PolishedStonesToBricks%22%2C%22SingularGlazedTerracotta%22%2C%22HorizontalWorldBorder%22%2C%22PlainLeatherArmor%22%2C%22GoldenCrown%22%2C%22ClassicNetheriteArmor%22%2C%22AllayElytra%22%2C%22PhantomElytra%22%2C%22ConcorpWings%22%2C%22EnderDragonElytra%22%5D%2C%22terrain.lower-and-sides%22%3A%5B%22LowerGrass%22%2C%22GrassSides%22%2C%22LowerMycelium%22%2C%22MyceliumSides%22%2C%22LowerPaths%22%2C%22PathSides%22%2C%22LowerPodzol%22%2C%22PodzolSides%22%2C%22LowerSnow%22%2C%22SnowSides%22%2C%22LowerCrimsonNylium%22%2C%22CrimsonNyliumSides%22%2C%22LowerWarpedNylium%22%2C%22WarpedNyliumSides%22%5D%2C%22terrain%22%3A%5B%22BushyLeaves%22%2C%22WavyLeaves%22%2C%22WavyPlants%22%2C%22WavyWater%22%2C%22DarkerDarkOakLeaves%22%2C%22GoldenSavanna%22%2C%22UniversalLushGrass%22%2C%22BetterBedrock%22%2C%22CircularSunandMoon%22%2C%22TwinklingStars%22%2C%22CircleLogTops%22%2C%22SmootherOakLog%22%2C%22SmootherStones%22%2C%22SmoothDirt%22%2C%22SmoothCoarseDirt%22%2C%22BrighterNether%22%2C%22ClearerWater%22%2C%22UniformOres%22%2C%22FancySunflowers%22%2C%22TallerSunflowers%22%2C%22ShorterGrass%22%2C%22ShorterTallGrass%22%2C%22WhiterSnow%22%5D%2C%22variation%22%3A%5B%22VariatedDirt%22%2C%22RandomCoarseDirtRotation%22%2C%22VariatedGrass%22%2C%22VariatedCobblestone%22%2C%22RandomMossRotation%22%2C%22VariatedBricks%22%2C%22VariatedLogs%22%2C%22VariatedMushroomBlocks%22%2C%22VariatedEndStone%22%2C%22VariatedGravel%22%2C%22VariatedMycelium%22%2C%22VariatedPlanks%22%2C%22VariatedStone%22%2C%22VariatedTerracotta%22%2C%22VariatedUnpolishedStones%22%2C%22VariatedBookshelves%22%2C%22RandomSunflowerRotation%22%2C%22VariatedVillagers%22%5D%2C%22connected-textures%22%3A%5B%22ConnectedBookshelves%22%2C%22ConnectedPolishedStones%22%2C%22ConnectedIronBlocks%22%2C%22ConnectedLapisBlocks%22%5D%2C%22utility%22%3A%5B%22DiminishingTools%22%2C%22MobSpawnIndicator%22%2C%22OreBorders%22%2C%22SuspiciousSandGravelBorders%22%2C%22BuddingAmethystBorders%22%2C%22VisualInfestedStoneItems%22%2C%22VisualWaxedCopperItems%22%2C%22Fullbright%22%2C%22FullAgeCropMarker%22%2C%22FullAgeAmethystMarker%22%2C%22DifferentStems%22%2C%22Age25Kelp%22%2C%22MineProgressBar%22%2C%22ClearBannerPatterns%22%2C%22HungerPreview%22%2C%22MusicDiscRedstonePreview%22%2C%22StickyPistonSides%22%2C%22DirectionalHoppers%22%2C%22DirectionalDispensersDroppers%22%2C%22BetterObservers%22%2C%22CleanRedstoneDust%22%2C%22RedstonePowerLevels%22%2C%22UnlitRedstoneOre%22%2C%22GroovyLevers%22%2C%22VisibleTripwires%22%2C%22CompassLodestone%22%2C%22BrewingGuide%22%2C%22VisualHoney%22%2C%22VisualCauldronStages%22%2C%22VisualComposterStages%22%2C%22VisualSaplingGrowth%22%2C%22NoteblockBanners%22%2C%22ArabicNumerals%22%5D%2C%22unobtrusive%22%3A%5B%22UnobtrusiveRain%22%2C%22UnobtrusiveSnow%22%2C%22UnobtrusiveParticles%22%2C%22NoCherryLeavesParticles%22%2C%22BorderlessGlass%22%2C%22BorderlessStainedGlass%22%2C%22BorderlessTintedGlass%22%2C%22CleanGlass%22%2C%22CleanStainedGlass%22%2C%22CleanTintedGlass%22%2C%22UnobtrusiveScaffolding%22%2C%22AlternateEnchantGlint%22%2C%22LowerFire%22%2C%22LowerShield%22%2C%22NoFog%22%2C%22TransparentPumpkin%22%2C%22NoPumpkinOverlay%22%2C%22TransparentSpyglassOverlay%22%2C%22NoSpyglassOverlay%22%2C%22NoVignette%22%2C%22NoBeaconBeam%22%2C%22CleanerWorldBorder%22%2C%22InvisibleTotem%22%2C%22SmallerUtilities%22%2C%22ShortSwords%22%5D%2C%223d%22%3A%5B%223DBookshelves%22%2C%223DChiseledBookshelves%22%2C%223DChains%22%2C%223DPointedDripstone%22%2C%223DAmethyst%22%2C%223DRedstoneWire%22%2C%223DTiles%22%2C%223DLadders%22%2C%223DRails%22%2C%223DSugarcane%22%2C%223DIronBars%22%2C%223DLilyPads%22%2C%223DDoors%22%2C%223DTrapdoors%22%2C%223DMushrooms%22%2C%223DVines%22%2C%223DGlowLichen%22%2C%223DSculkVein%22%2C%223DStonecutters%22%2C%223DSunMoon%22%5D%2C%22fixes-and-consistency%22%3A%5B%22ItemStitchingFix%22%2C%22JappaObserver%22%2C%22JappaToasts%22%2C%22JappaStatsIcons%22%2C%22JappaSpecIcons%22%2C%22RedstoneWireFix%22%2C%22DripleafFixBig%22%2C%22DripleafFixSmall%22%2C%22ConsistentUIFix%22%2C%22ConsistentDecorPot%22%2C%22ConsistentBucketFix%22%2C%22ConsistentTadpoleBucket%22%2C%22CactusBottomFix%22%2C%22ConsistentHelmets%22%2C%22BrighterRibTrim%22%2C%22HangingSignLogs%22%2C%22PixelConsistentBat%22%2C%22PixelConsistentGhast%22%2C%22PixelConsistentElderGuardian%22%2C%22PixelConsistentWither%22%2C%22TripwireHookFix%22%2C%22PixelConsistentSigns%22%2C%22PixelConsistentXPOrbs%22%2C%22PixelConsistentBeaconBeam%22%2C%22PixelConsistentSonicBoom%22%2C%22PixelConsistentGuardianBeam%22%2C%22SoulSoilSoulCampfire%22%2C%22BlazeFix%22%2C%22SlimeParticleFix%22%2C%22NicerFastLeaves%22%2C%22ProperBreakParticles%22%2C%22NoBowlParticles%22%2C%22IronBarsFix%22%2C%22ConsistentSmoothStone%22%2C%22DoubleSlabFix%22%2C%22ItemHoldFix%22%2C%22HoeFix%22%2C%22CloudFogFix%22%5D%7D&version=1.20",
                        }
                    )
                    .then((response) => response.json())
                    .then(
                        (data) => "http://localhost:8079/https://vanillatweaks.net" + data.link
                    );
            }

            //cached 30 minute vanilla tweaks resource pack link
            async function getCachedVanillaTweaksResourcePackLink() {
                const cachedLink = localStorage.getItem("vanillaTweaksResourcePackLink");
                const cachedTime = localStorage.getItem("vanillaTweaksResourcePackLinkTime");
                const isCacheTimeValid = (cachedTime) => {
                    const currentTime = new Date().getTime();
                    const timeDifference = currentTime - cachedTime;
                    const timeDifferenceInMinutes = timeDifference / 1000 / 60;
                    return timeDifferenceInMinutes < 30;
                };
                if (cachedLink && isCacheTimeValid(cachedTime)) {
                    return cachedLink;
                } else {
                    const vanillaTweaksResourcePackLink =
                        await getVanillaTweaksResourcePackLinks();
                    localStorage.setItem(
                        "vanillaTweaksResourcePackLink",
                        vanillaTweaksResourcePackLink
                    );
                    localStorage.setItem(
                        "vanillaTweaksResourcePackLinkTime",
                        String(new Date().getTime())
                    );
                    return vanillaTweaksResourcePackLink;
                }
            }

            async function getRessourcePackLinks() {
                const vanillaTweaksResourcePackLink =
                    await getCachedVanillaTweaksResourcePackLink();
                const vanillaPack =
                    "http://localhost:8079/https://www.curseforge.com/api/v1/mods/457153/files/5008188/download";
                const packs = [];
                packs.push(vanillaTweaksResourcePackLink);
                packs.push(vanillaPack);
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

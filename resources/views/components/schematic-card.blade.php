<div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <x-schematic-renderer :schematic="$schematic" />
    <div class="px-5 pb-5">
        <a href="#">
            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                {{ $schematic->name }}
            </h5>
        </a>
        <div class="flex items-center mt-2.5 mb-5">
            <div class="flex items-center space-x-1 rtl:space-x-reverse">
                @foreach ($schematic->authors as $player)
                    <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}" class="w-8 h-8 rounded-full m-1">
                @endforeach
            </div>
            <span
                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3">
                tags-tbd
            </span>
        </div>
        <div class="flex items-center justify-between">
            {{--  <span class="text-3xl font-bold text-gray-900 dark:text-white">$599</span>  --}}
            <a href="/schematics/{{ $schematic->id }}" wire:navigate
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                View
            </a>
        </div>
    </div>
</div>
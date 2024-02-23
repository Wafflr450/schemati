<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;

state(['search'])->url();

// TODO: Optimise and use full-text search
$schematics = computed(function () {
    return Schematic::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('description', 'like', '%' . $this->search . '%')
        ->orWhereHas('authors', function ($query) {
            $query->where('last_seen_name', 'like', '%' . $this->search . '%');
        })
        ->get();
});

$deleteSchematic = function ($schematicId) {
    $schematic = \App\Models\Schematic::find($schematicId);
    if (!$schematic) {
        return;
    }
    $schematic->delete();
    state('schematics', $this->schematics->filter(fn($s) => $s->id !== $schematicId));
    $this->dispatch('schematicDeleted');
};

?>

<x-app-layout>
    @volt
        <div class="p-4">
            <div class="p-4 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Schematics
                    </h1>
                    <div>
                        <input type="text" wire:model.live="search"
                            class="w-full p-2 mt-2 text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 rounded-lg"
                            placeholder="Search for a schematic...">
                    </div>
                </div>
                <div class="p-4">
                    @foreach ($this->schematics as $schematic)
                        <div>
                            <div class="flex flex-row justify-between items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg mb-4"
                                wire:key="{{ $schematic->id }}">
                                <div>
                                    <div class="flex flex-row items-center">
                                        <h2
                                            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight pr-2">
                                            {{ $schematic->name }}
                                        </h2>
                                        @foreach ($schematic->authors as $player)
                                            <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}"
                                                class="w-6 h-6 inline-block pr-1"
                                                data-tooltip-target="tooltip-{{ $player->id }}-{{ $schematic->id }}">
                                            <div id="tooltip-{{ $player->id }}-{{ $schematic->id }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                <p>{{ $player->lastSeenName }}</p>
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ $schematic->description }}
                                    </p>
                                </div>
                                <div class="flex flex-row items-center bg-gray-200 dark:bg-gray-400 rounded-lg p-2">
                                    <a href="{{ $schematic->downloadLink }}" target="_blank"
                                        class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-blue-700 md:p-2 dark:text-white">
                                        <i class="fas fa-download text-blue-700"></i>
                                    </a>
                                    @auth
                                        @if ($schematic->authors->contains(auth()->user()->player->id))
                                            <button wire:click="deleteSchematic('{{ $schematic->id }}')"
                                                class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-red-700 md:p-2 dark:text-white">
                                                <i class="fas fa-trash text-red-700"></i>
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            <x-schematic-renderer :schematic="$schematic" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endvolt
</x-app-layout>

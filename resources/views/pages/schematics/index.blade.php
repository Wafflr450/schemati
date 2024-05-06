<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;
use function Laravel\Folio\name;

name('schematics');

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
?>

<x-app-layout>
    @volt
        <div class="max-w-7xl mx-auto pt-4">
            <div class="bg-base-300 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Schematics
                    </h1>
                    <div>
                        <input type="text" wire:model.live="search"
                            class="w-full p-2 mt-2 text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 rounded-lg"
                            placeholder="Search for a schematic...">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                    @foreach ($this->schematics as $schematic)
                        <x-schematic-card :schematic="$schematic" />
                    @endforeach
                </div>
            </div>
        </div>
    @endvolt
</x-app-layout>

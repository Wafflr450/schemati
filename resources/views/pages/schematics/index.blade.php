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
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-neutral-900 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-white mb-6 text-center">
                            Discover Schematics
                        </h1>
                        <div class="max-w-xl mx-auto mb-8">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" wire:model.live="search"
                                    class="block w-full pl-10 pr-3 py-2 border border-neutral-700 rounded-md leading-5 bg-neutral-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="Search for a schematic...">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-8">
                        @forelse ($this->schematics as $schematic)
                            <x-schematic-card :schematic="$schematic" />
                        @empty
                            <div class="col-span-full text-center text-gray-400">
                                <p class="text-lg mb-2">No schematics found</p>
                                <p>Try adjusting your search or explore our featured schematics.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endvolt
</x-app-layout>

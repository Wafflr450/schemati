<?php

use function Livewire\Volt\{mount, state};
use App\Models\Schematic;

state('schematics', Schematic::all());

$deleteSchematic = function ($schematicId) {
    $schematic = Schematic::find($schematicId);
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
        <div
            class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg m-4">
            @foreach ($schematics as $schematic)
                <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg m-4">
                    <h2>{{ $schematic->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 p-2">
                        {{ $schematic->id }}</p>
                    <p>{{ $schematic->description }}</p>
                    <a href="{{ $schematic->downloadLink }}" target="_blank"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download</a>

                    <button wire:click="deleteSchematic('{{ $schematic->id }}')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete</button>
                </div>
            @endforeach
        </div>
    @endvolt

</x-app-layout>

<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\Schematic;

state(['schematic' => fn() => $schematic, 'schematicName' => '']);

mount(function () {
    $this->schematicName = $this->schematic->name;
});

$deleteSchematic = function ($schematicId) {
    $schematic = \App\Models\Schematic::find($schematicId);
    if (!$schematic) {
        return;
    }
    $schematic->delete();

    $this->redirect('/schematics');
};

$changeSchematicName = function ($schematicId) {
    $schematic = \App\Models\Schematic::find($schematicId);
    if (!$schematic) {
        return;
    }
    $schematic->name = $this->schematicName;
    $schematic->save();
};

?>

<x-app-layout>


    <div class="flex justify-center p-4">
        @volt
            <div class="bg-neutral rounded-lg shadow-lg flex flex-col p-4 w-full">
                @if ($schematic->authors->contains(Auth::user()->uuid))
                    <input type="text" class="font-semibold text-lg text-gray-80 mb-2 bg-transparent border-none"
                        wire:model="schematicName" wire:change="changeSchematicName('{{ $schematic->id }}')">
                @else
                    <h2 class="font-semibold text-lg text-gray-80 mb-2">
                        {{ $schematic->name }}
                    </h2>
                @endif
                <div class="flex-grow shadow-[inset_0_4px_4px_rgba(1,0,0,0.6)] rounded-lg bg-neutral">
                    <x-schematic-renderer :schematic="$schematic" />
                </div>
                <div class="flex justify-center p-4">
                    <p class="mb-4 text-center cursor-pointer bg-neutral  p-2 rounded-lg active:bg-gray-300 hover:bg-gray-300"
                        onclick="copyToClipboard('/download {{ $schematic->id }}'); Toast.success('Copied to clipboard')">
                        /download {{ $schematic->id }}
                    </p>
                </div>

                <div class="flex justify-center p-4">
                    <p class="mb-4 text-center cursor-pointer bg-neutral  p-2 rounded-lg active:bg-gray-300 hover:bg-gray-300"
                        onclick="copyToClipboard('{{ $schematic->base64 }}'); Toast.success('Copied to clipboard')">
                        Copy Base64
                    </p>
                </div>

                <div class="flex justify-center p-4">
                    <a href="{{ $schematic->downloadLink }}" target="_blank" class="text-blue-700  hover:underline">
                        Download <i class="fas fa-download text-green-700"></i>
                    </a>
                    <h1>

                        @auth
                            @if ($schematic->authors->contains(Auth::user()->uuid))
                                <button wire:click="deleteSchematic('{{ $schematic->id }}')" class="ml-4 text-red-700 ">
                                    Delete <i class="fas fa-trash text-red-700"></i>

                                </button>
                            @endif
                        @endauth

                </div>
                <div>
                    <div class="flex flex-wrap justify-center">
                        @foreach ($schematic->authors as $player)
                            <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}"
                                class="w-8 h-8 rounded-full m-1">
                        @endforeach
                    </div>
                    <p class="text-gray-500  mb-4 text-center">
                        {!! $schematic->description !!}
                    </p>
                </div>
            </div>
        @endvolt
    </div>

</x-app-layout>

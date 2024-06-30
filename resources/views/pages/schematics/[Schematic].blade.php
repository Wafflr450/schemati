<?php

use function Livewire\Volt\{state, computed};
use App\Models\Schematic;

state(['schematic' => fn() => $schematic]);

$deleteSchematic = function ($schematicId) {
    $schematic = \App\Models\Schematic::find($schematicId);
    if (!$schematic) {
        return;
    }
    $schematic->delete();

    $this->redirect('/schematics');
};

$relatedSchematics = computed(function () {
    // This is a placeholder. Implement your logic to fetch related schematics.
    return Schematic::where('id', '!=', $this->schematic->id)
        ->inRandomOrder()
        ->limit(4)
        ->get();
});

?>

<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @volt
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Schematic Title and Renderer Card -->
                        <div class="bg-neutral-800 rounded-lg shadow-lg overflow-hidden">
                            <div class="p-6 flex justify-between items-start">
                                <h1 class="text-3xl font-bold text-white">
                                    {{ $schematic->name }}
                                </h1>
                                @auth
                                    @if ($schematic->authors->contains(Auth::user()->uuid))
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open"
                                                class="text-gray-400 hover:text-white focus:outline-none">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 bg-neutral-700 rounded-md overflow-hidden shadow-xl z-10">
                                                <a href="/"
                                                    class="block px-4 py-2 text-sm text-white hover:bg-neutral-600">Edit
                                                    Schematic</a>
                                                <button
                                                    @click="if(confirm('Are you sure you want to delete this schematic? This action cannot be undone.')) { $wire.deleteSchematic('{{ $schematic->id }}') }"
                                                    class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-neutral-600">
                                                    Delete Schematic
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <x-schematic-renderer :schematic="$schematic" />
                        </div>

                        <!-- Description Card -->
                        <div class="bg-neutral-800 rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Description</h2>
                            <div class="text-gray-300 prose lg:prose-xl">
                                {!! $schematic->description !!}
                            </div>
                        </div>

                        <!-- Related Schematics Section -->
                        <div class="bg-neutral-800 rounded-lg shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-white mb-6">Related Schematics</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach ($this->relatedSchematics as $relatedSchematic)
                                    <div class="bg-neutral-700 rounded-lg overflow-hidden">
                                        <img src="{{ $relatedSchematic->thumbnail_url }}"
                                            alt="{{ $relatedSchematic->name }}" class="w-full h-40 object-cover">
                                        <div class="p-4">
                                            <h3 class="text-white font-semibold mb-2">{{ $relatedSchematic->name }}</h3>
                                            <a href="/schematics/{{ $relatedSchematic->id }}"
                                                class="text-primary hover:text-primary-600">View Schematic</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Column -->
                    <div class="space-y-8">
                        <div class="bg-neutral-800 rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Schematic Info</h3>

                            <!-- Visibility -->
                            <div class="mb-4">
                                <span class="text-gray-400 text-sm">Visibility:</span>
                                <span class="ml-2 text-white">
                                    @if ($schematic->is_public)
                                        <i class="fas fa-globe text-green-500"></i> Public
                                    @else
                                        <i class="fas fa-lock text-yellow-500"></i> Private
                                    @endif
                                </span>
                            </div>

                            <!-- Authors -->
                            <div class="mb-4">
                                <h4 class="text-md font-semibold text-gray-300 mb-2">Authors</h4>
                                <div class="flex flex-wrap">
                                    @foreach ($schematic->authors as $player)
                                        <div class="flex items-center bg-neutral-700 rounded-full py-1 px-3 m-1">
                                            <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}"
                                                class="w-6 h-6 rounded-full mr-2">
                                            <span class="text-white text-sm">{{ $player->lastSeenName }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tags -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-300 mb-2">Tags</h4>
                                <div class="flex flex-wrap">
                                    @foreach ($schematic->tags as $tag)
                                        <span class="text-sm rounded-full px-3 py-1 m-1"
                                            style="background-color: {{ $tag->color ?? '#374151' }}; color: {{ $tag->text_color ?? '#ffffff' }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Download Actions Card -->
                        <div class="bg-neutral-800 rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Download</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-400 text-sm block mb-2">In-game command:</span>
                                    <div class="flex justify-between items-center bg-neutral-700 rounded p-2">
                                        <code class="text-white text-sm">/download {{ $schematic->short_id }}</code>
                                        <button onclick="copyToClipboard('/download {{ $schematic->short_id }}'); "
                                            class="text-white hover:text-primary-600 focus:outline-none">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <button onclick="copyToClipboard('{{ $schematic->base64 }}'); "
                                    class="w-full bg-neutral-700 hover:bg-neutral-600 text-white py-2 px-4 rounded transition duration-150 ease-in-out">
                                    Copy Base64
                                </button>
                                <a href="{{ $schematic->downloadLink }}" target="_blank"
                                    class="block w-full bg-primary hover:bg-primary-600 text-white text-center py-2 px-4 rounded transition duration-150 ease-in-out">
                                    Download File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endvolt
        </div>
    </div>
</x-app-layout>

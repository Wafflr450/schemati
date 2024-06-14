<?php
use function Livewire\Volt\{state, mount, updated};

state(['player' => fn() => auth()->user()->player, 'rootNodes' => fn() => $this->player->topMostAdminTags, 'selectedRootNode' => fn() => $this->rootNodes[0]]);

mount(function () {
    $this->player = auth()->user()->player;
});

$clicked = function () {
    dd('Clicked');
};

?>

<div>
    @volt
        <div>
            <button wire:click="clicked()">Click me</button>
            <div>
                <select wire:model="selectedRootNode">
                    @foreach ($rootNodes as $rootNode)
                        <option value="{{ $rootNode->id }}">{{ $rootNode->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endvolt
</div>

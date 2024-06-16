<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

use App\Models\Player;
use App\Models\Tag;

new class extends Component {
    public $tag;
    public $selectedAdmins = [];
    public $selectedUsers = [];
    public $selectedViewers = [];

    public $showSearch = false;
    public $searchRole = '';

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
        $this->selectedAdmins = $tag->admins()->pluck('id')->toArray();
        $this->selectedUsers = $tag->users() instanceof \Illuminate\Database\Eloquent\Collection ? $tag->users()->pluck('id')->toArray() : [];
        $this->selectedViewers = $tag->viewers() instanceof \Illuminate\Database\Eloquent\Collection ? $tag->viewers()->pluck('id')->toArray() : [];
    }

    #[On('playerSelected')]
    public function handlePlayerSelected($player)
    {
        if (!in_array($player['id'], $this->{$this->searchRole})) {
            $this->{$this->searchRole}[] = $player['id'];
        }
        $this->syncRoles();
        $this->showSearch = false;
        $this->dispatch('clearSearch');
    }

    public function syncRoles()
    {
        $this->tag->admins()->sync($this->selectedAdmins, false);
        if (!$this->tag->public_use) {
            $this->tag->users()->sync($this->selectedUsers, false);
        }
        if (!$this->tag->public_viewing && !$this->tag->public_use) {
            $this->tag->viewers()->sync($this->selectedViewers, false);
        }
    }

    public function showPlayerSearch($role)
    {
        $this->searchRole = $role;
        $this->showSearch = true;
    }

    public function removePlayer($role, $playerId)
    {
        $this->{$role} = array_diff($this->{$role}, [$playerId]);
        $this->syncRoles();
    }
};
?>

<div>
    <h2 class="text-2xl font-semibold text-gray-200">Player Tag Permission Manager for {{ $tag->name }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-200">Admins</label>
            <ul class="bg-neutral-800 rounded-lg p-4 space-y-2">
                @foreach ($selectedAdmins as $playerId)
                    <li class="flex items-center justify-between p-2 border-b border-gray-700 last:border-none">
                        <div class="flex items-center">
                            <img src="{{ Player::find($playerId)->head_url }}" alt="Player Head"
                                class="w-8 h-8 rounded-full mr-4">
                            <p class="text-gray-200 font-semibold">{{ Player::find($playerId)->last_seen_name }}</p>
                        </div>
                        <button wire:click="removePlayer('selectedAdmins', '{{ $playerId }}')"
                            class="bg-red-500 hover:bg-red-600 text-white text-sm px-2 py-1 rounded">Remove</button>
                    </li>
                @endforeach
                <li class="text-center">
                    <button wire:click="showPlayerSearch('selectedAdmins')"
                        class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary">Add
                        Admin</button>
                </li>
            </ul>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-200">Users</label>
            <ul class="bg-neutral-800 rounded-lg p-4 space-y-2">
                @foreach ($selectedUsers as $playerId)
                    <li class="flex items-center justify-between p-2 border-b border-gray-700 last:border-none">
                        <div class="flex items-center">
                            <img src="{{ Player::find($playerId)->head_url }}" alt="Player Head"
                                class="w-8 h-8 rounded-full mr-4">
                            <p class="text-gray-200 font-semibold">{{ Player::find($playerId)->last_seen_name }}</p>
                        </div>
                        <button wire:click="removePlayer('selectedUsers', '{{ $playerId }}')"
                            class="bg-red-500 hover:bg-red-600 text-white text-sm px-2 py-1 rounded">Remove</button>
                    </li>
                @endforeach
                <li class="text-center">
                    <button wire:click="showPlayerSearch('selectedUsers')"
                        class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary">Add
                        User</button>
                </li>
            </ul>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-200">Viewers</label>
            <ul class="bg-neutral-800 rounded-lg p-4 space-y-2">
                @foreach ($selectedViewers as $playerId)
                    <li class="flex items-center justify-between p-2 border-b border-gray-700 last:border-none">
                        <div class="flex items-center">
                            <img src="{{ Player::find($playerId)->head_url }}" alt="Player Head"
                                class="w-8 h-8 rounded-full mr-4">
                            <p class="text-gray-200 font-semibold">{{ Player::find($playerId)->last_seen_name }}</p>
                        </div>
                        <button wire:click="removePlayer('selectedViewers', '{{ $playerId }}')"
                            class="bg-red-500 hover:bg-red-600 text-white text-sm px-2 py-1 rounded">Remove</button>
                    </li>
                @endforeach
                <li class="text-center">
                    <button wire:click="showPlayerSearch('selectedViewers')"
                        class="bg-primary rounded-lg px-4 py-2 text-sm font-semibold hover:bg-secondary">Add
                        Viewer</button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Player Search Modal -->
    <div x-data="{ open: @entangle('showSearch') }" x-show="open" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open"
                class="inline-block align-bottom bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-200" id="modal-title">Search Player</h3>
                            <div class="mt-2">
                                <livewire:playersearch wire:emit="clearSearch" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="open = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-gray-200 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

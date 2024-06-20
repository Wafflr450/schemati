<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

use App\Models\Player;
use App\Utils\MinecraftAPI;
use App\Utils\CommonUtils;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public $search = '';
    public $players = [];
    public $selectedPlayer = null;

    public function updatedSearch()
    {
        if ($this->selectedPlayer) {
            return;
        }

        $this->players = [];

        if (strlen($this->search) < 3) {
            return;
        }

        if (CommonUtils::isUUID($this->search)) {
            $this->players = Cache::remember("player-search-uuid-{$this->search}", now()->addDay(), function () {
                return Player::firstOrCreate(['id' => $this->search]);
            });

            if ($this->players) {
                $this->players = [$this->players->toArray()];
            }
        } else {
            $this->players = Player::where('last_seen_name', 'like', "{$this->search}%")
                ->get()
                ->toArray();

            if (empty($this->players)) {
                $player = Cache::remember("player-search-username-{$this->search}", now()->addDay(), function () {
                    $uuid = MinecraftAPI::getUUID($this->search);
                    if ($uuid) {
                        return Player::firstOrCreate(['id' => $uuid]);
                    }
                    return null;
                });

                if ($player) {
                    $this->players[] = $player->toArray();
                }
            }
        }
    }

    public function selectPlayer($playerId)
    {
        $player = Player::find($playerId);
        if ($player) {
            $this->selectedPlayer = $player->toArray();
            $this->dispatch('playerSelected', player: $this->selectedPlayer);
        }
    }

    public function resetSearch()
    {
        $this->selectedPlayer = null;
        $this->search = '';
        $this->players = [];
    }

    #[On('clearSearch')]
    public function clearSearch()
    {
        $this->resetSearch();
    }
};
?>

<div>
    @if ($selectedPlayer)
        <div class="p-4 bg-neutral-800 rounded-lg text-gray-200 shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ $selectedPlayer['head_url'] }}" alt="Player Head" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <p class="font-semibold">{{ $selectedPlayer['last_seen_name'] }}</p>
                        <p class="text-gray-400 text-sm">UUID: {{ $selectedPlayer['id'] }}</p>
                    </div>
                </div>
                <button wire:click="resetSearch" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded">
                    Dismiss
                </button>
            </div>
        </div>
    @else
        <input type="text" wire:model.live="search" class="w-full p-2 border border-gray-300 rounded"
            placeholder="Search for a player...">

        <div class="mt-2 bg-neutral-800 " wire:loading.remove>
            @if (!empty($players))
                <ul class="rounded-lg p-4">
                    @foreach ($players as $player)
                        <li class="p-2 border-b border-gray-700 last:border-none cursor-pointer"
                            wire:click="selectPlayer('{{ $player['id'] }}')">
                            <div class="flex items-center">
                                <img src="{{ $player['head_url'] }}" alt="Player Head"
                                    class="w-8 h-8 rounded-full mr-4">
                                <div>
                                    <p class="text-gray-200 font-semibold">{{ $player['last_seen_name'] }}</p>
                                    <p class="text-gray-400 text-sm">UUID: {{ $player['id'] }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @elseif (strlen($search) >= 3)
                <p class="text-gray-400">No players found.</p>
            @endif
        </div>
        <div class="mt-2 bg-neutral-800 " wire:loading>
            <p class="text-gray-400">Searching for players <i class="fas fa-spinner animate-spin"></i> </p>
        </div>
    @endif
</div>

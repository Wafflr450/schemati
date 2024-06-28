<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Tag;
use Illuminate\Console\Command;
use function Laravel\Prompts\search;
use function Laravel\Prompts\confirm;

class AssignTagAdmin extends Command
{
    protected $signature = 'tag:assign-admin';
    protected $description = 'Assign a player as an admin to a tag';

    public function handle()
    {
        $tag = $this->searchTag();
        if (!$tag) {
            $this->error('No tag selected. Aborting.');
            return;
        }

        $player = $this->searchPlayer();
        if (!$player) {
            $this->error('No player selected. Aborting.');
            return;
        }

        if ($tag->admins()->where('player_id', $player->id)->exists()) {
            $this->warn("Player '{$player->last_seen_name}' is already an admin of the '{$tag->name}' tag.");
            if (!confirm("Do you want to remove them as an admin?")) {
                return;
            }
            $tag->admins()->detach($player);
            $this->info("Player '{$player->last_seen_name}' has been removed as an admin from the '{$tag->name}' tag.");
        } else {
            $tag->admins()->attach($player);
            $this->info("Player '{$player->last_seen_name}' has been assigned as an admin to the '{$tag->name}' tag.");
        }
    }

    private function searchTag()
    {
        $tagId = search(
            label: 'Search for a tag',
            options: function ($value) {
                return Tag::where('name', 'like', "%{$value}%")
                    ->orWhere('description', 'like', "%{$value}%")
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($tag) {
                        return [$tag->id => "{$tag->name} - {$tag->description}"];
                    })
                    ->all();
            },
            placeholder: 'Start typing to search for tags...'
        );

        return Tag::find($tagId);
    }

    private function searchPlayer()
    {
        $playerId = search(
            label: 'Search for a player',
            options: function ($value) {
                return Player::where('last_seen_name', 'like', "%{$value}%")
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($player) {
                        return [$player->id => $player->last_seen_name];
                    })
                    ->all();
            },
            placeholder: 'Start typing to search for players...'
        );

        return Player::find($playerId);
    }
}

<?php

namespace App\Console\Commands;

use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\form;

use App\Utils\MinecraftAPI;
use Illuminate\Console\Command;
use App\Http\Controllers\PasswordSetSession;

class GetPlayerPasswordSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-player-password-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns a password set link for a given uuid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $responses = form()->text('What is the player Name?', required: true, name: 'player_name')->submit();

        $uuid = MinecraftAPI::getUUID($responses['player_name']);
        $this->info($uuid);
        $this->info(PasswordSetSession::getPasswordSetSessionLink($uuid));
    }
}

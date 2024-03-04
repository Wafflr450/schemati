<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class DiscordWebhook
{
    public static function send(string $message, string $username = 'Schemat', string $avatar = null, string $url = null): bool
    {
        $url ??= config('app.discord_webhook_url');
        $avatar = $avatar ?? 'https://ibb.co/BV636Rv';
        $response = Http::post($url, [
            'username' => $username,
            'avatar_url' => $avatar,
            'content' => $message,
        ]);

        return $response->status() === 204;
    }

    public static function sendEmbed(array $embed, string $username = 'Schemat', string $avatar = null, string $url = null): bool
    {
        $url ??= config('app.discord_webhook_url');
        $avatar = $avatar ?? 'https://ibb.co/BV636Rv';

        $response = Http::post($url, [
            'username' => $username,
            'avatar_url' => $avatar,
            'embeds' => [$embed],
        ]);

        return $response->status() === 204;
    }
}

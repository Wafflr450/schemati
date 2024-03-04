<?php

namespace App\Utils;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MinecraftAPI
{
    const MOJANG_API = 'https://api.mojang.com';

    public static function getUsername(string $uuid): string|null
    {
        return Cache::rememberForever("username-{$uuid}", function () use ($uuid) {
            $endpoint = self::MOJANG_API . "/user/profile/{$uuid}";
            $response = Http::get($endpoint);
            if ($response->status() !== 200) {
                return null;
            }
            return $response->json()['name'];
        });
    }

    public static function getHeadImageURL(string $uuid): string
    {
        return "https://mc-heads.net/avatar/{$uuid}";
    }
}

<?php

namespace App\Utils;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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

    public static function getUUID(string $username, $hyphens = true): string|null
    {
        $cacheTime = Carbon::now()->addWeek();
        return Cache::remember("uuid-{$username}-{($hyphens ? 'hyphens' : 'no-hyphens')}", $cacheTime, function () use ($username, $hyphens) {
            $endpoint = self::MOJANG_API . "/users/profiles/minecraft/{$username}";
            $response = Http::get($endpoint);
            if ($response->status() !== 200) {
                return null;
            }
            $uuid = $response->json()['id'];
            if ($uuid && $hyphens) {
                $uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20);
            }
            return $uuid;
        });
    }

    public static function getHeadImageURL(string $uuid): string
    {
        return "https://mc-heads.net/avatar/{$uuid}";
    }
}

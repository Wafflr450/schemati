<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class CommonUtils
{
    public static function isUUID(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    public static function smallHash(string $input, int $length = 8): string
    {
        return substr(md5($input), 0, $length);
    }

    public static function randomString(int $length = 8): string
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
    }
}

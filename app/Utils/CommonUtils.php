<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CommonUtils
{
    public static function isUUID(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    public static function smallHash(string $input, int $length = 6): string
    {
        $base64 = base64_encode(md5($input, true));
        $base64url = strtr($base64, '+/', '-_');
        return substr($base64url, 0, $length);
    }

    public static function randomString(int $length = 8): string
    {
        return Str::random($length);
    }
}

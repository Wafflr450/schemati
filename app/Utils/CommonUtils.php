<?php

namespace App\Utils;

class CommonUtils
{
    public static function isUUID(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    public static function smallHash(string $input): string
    {
        return substr(md5($input), 0, 8);
    }
}

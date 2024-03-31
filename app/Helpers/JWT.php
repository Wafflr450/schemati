<?php
namespace App\Helpers;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JWT
{
    public static function getTestToken()
    {
        $dummyPayload = [
            'iss' => 'http://localhost',
            'iat' => time(),
            'tag_read_list' => ['*', 'ore:*'],
            'tag_write_list' => ['ore:*'],
        ];

        return FirebaseJWT::encode($dummyPayload, config('app.jwt_secret'), 'HS256');
    }

    public static function getToken($payload)
    {
        return FirebaseJWT::encode($payload, config('app.jwt_secret'), 'HS256');
    }
}

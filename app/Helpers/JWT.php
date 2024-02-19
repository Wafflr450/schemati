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
        ];

        return FirebaseJWT::encode($dummyPayload, config('app.jwt_secret'), 'HS256');
    }
}

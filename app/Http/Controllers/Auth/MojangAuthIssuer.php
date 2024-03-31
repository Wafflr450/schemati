<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JWT;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MojangAuthIssuerRequest;

class MojangAuthIssuer extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MojangAuthIssuerRequest $request)
    {
        $client = new Client();
        $response = $client->get("https://sessionserver.mojang.com/session/minecraft/hasJoined?username={$request->username}&serverId={$request->serverId}");

        if ($response->getStatusCode() !== 200) {
            return response()->json(
                [
                    'error' => 'Mojang API returned an error',
                ],
                401
            );
        }

        $result = json_decode($response->getBody(), true);

        return response()->json([
            'token' => JWT::getToken([
                'username' => $result['name'],
                'uuid' => $result['id'],
            ])
        ], 200);
    }
}

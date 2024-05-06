<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordSetSessionRequest;
use App\Utils\CommonUtils;
use Illuminate\Support\Facades\Cache;

class PasswordSetSession extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordSetSessionRequest $request)
    {
        $shortCode = CommonUtils::randomString(8);
        $cacheKey = "password-set-session:{$shortCode}";
        Cache::put($cacheKey, $request->player_uuid, now()->addMinutes(5));
        $link = route('set-password') . "?session={$shortCode}";
        return response()->json(
            [
                'link' => $link,
            ],
            200,
        );
    }
}

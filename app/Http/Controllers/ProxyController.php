<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    public function fetchResourcePack(Request $request)
    {
        $url = $request->get('url');

        if (!$url) {
            return response()->json(['error' => 'URL parameter is required'], 400);
        }

        try {
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification (use with caution)
            ])->get($url);

            if ($response->successful()) {
                return $response->body();
            } else {
                return response()->json(['error' => 'Failed to fetch resource pack'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

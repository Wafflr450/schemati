<?php

namespace App\Http\Controllers\Schematic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Schematic\SchematicUploadRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Utils\CommonUtils;

class SchematicUpload extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SchematicUploadRequest $request)
    {
        $schematicUUID = Str::uuid();
        $author = $request->author;
        $cacheKey = "schematic-temporary:{$author}:{$schematicUUID}";
        $fileContents = file_get_contents($request->file('schematic'));
        Cache::put($cacheKey, $fileContents, now()->addHours(1));
        $smallCacheKey = CommonUtils::smallHash($cacheKey, 6);
        Cache::put("schematic-temporary-short-links:{$smallCacheKey}", $cacheKey, now()->addHours(1));
        $link = route('index') . "/schematics/upload/{$smallCacheKey}";
        return response()->json(
            [
                'link' => $link,
            ],
            200,
        );
    }
}

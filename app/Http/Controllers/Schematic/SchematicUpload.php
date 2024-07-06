<?php

namespace App\Http\Controllers\Schematic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Schematic\SchematicUploadRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Utils\CommonUtils;
use Illuminate\Http\UploadedFile;

/**
 * @tags Schematic
 */
class SchematicUpload extends Controller
{
    /**
     * Schematic Temporary Upload
     *
     * This endpoint is used to temporarily upload a schematic file.
     */
    public function __invoke(SchematicUploadRequest $request)
    {
        $schematicFile = $request->file('schematic');
        $author = $request->author;

        $link = self::cacheSchematic($schematicFile->getRealPath(), $author);

        return response()->json(
            [
                'link' => $link,
            ],
            200,
        );
    }

    /**
     * Cache the schematic file and return the link.
     *
     * @param string $schematicFile
     * @param string $author
     * @return string
     */
    public static function cacheSchematic(string $schematicFileRealPath, string $author): string
    {
        $schematicUUID = Str::uuid();
        $cacheKey = "schematic-temporary:{$author}:{$schematicUUID}";
        $fileContents = file_get_contents($schematicFileRealPath);

        Cache::put($cacheKey, $fileContents, now()->addHours(1));

        $smallCacheKey = CommonUtils::smallHash($cacheKey, 6);
        Cache::put("schematic-temporary-short-links:{$smallCacheKey}", $cacheKey, now()->addHours(1));

        return route('index') . "/schematics/upload/{$smallCacheKey}";
    }
}

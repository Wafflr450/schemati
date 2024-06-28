<?php

namespace App\Http\Controllers\Schematic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schematic\SchematicCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

use App\Models\Schematic;
use App\Models\Player;

/**
 * @tags Schematic
 */
class SchematicCreate extends Controller
{
    /**
     * ( Deprecated ) Schematic Create
     */
    public function __invoke(SchematicCreateRequest $request)
    {
        $schematicUUID = Str::uuid();
        $authors = explode(',', $request->authors);
        $schematic = new Schematic([
            'id' => $schematicUUID,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $uploadResult = Storage::disk('schematics')->putFileAs('schematics', $request->file('schematic'), $schematicUUID . '.schem');

        if (!$uploadResult) {
            return response()->json(
                [
                    'error' => 'Failed to upload schematic.',
                ],
                500,
            );
        }
        $schematic->save();
        $authors = array_map(function ($author) {
            return Player::firstOrCreate(['id' => $author])->id;
        }, $authors);
        Schematic::find($schematicUUID)->authors()->attach($authors);
    }
}

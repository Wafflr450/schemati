<?php

namespace App\Http\Controllers\Schematic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schematic\SchematicDownloadRequest;
use App\Models\Schematic;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Utils\CommonUtils;
use App\Services\SchematicSerializers\SchematicSerializerInterface;
use App\Services\SchematicSerializers\SchematicSerializer;
use App\Services\SchematicSerializers\SchemSerializer;
/**
 * @tags Schematic
 */
class SchematicDownload extends Controller
{
    public function __invoke(SchematicDownloadRequest $request): StreamedResponse
    {
        $id = $request->validated('id');
        $format = $request->input('format', 'schem');

        $schematic = $this->findSchematic($id);

        $schematicFile = $schematic->file;

        $serializer = $this->getSerializer($format);

        return response()->streamDownload(
            function () use ($schematicFile, $serializer) {
                echo $serializer->serialize($schematicFile);
            },
            $schematic->name . '.' . $serializer->getFileExtension(),
            [
                'Content-Type' => $serializer->getMimeType(),
            ],
        );
    }

    private function findSchematic($id): Schematic
    {
        if (CommonUtils::isUUID($id)) {
            return Schematic::findOrFail($id);
        }

        return Schematic::where('short_id', $id)->orWhere('slug', $id)->firstOrFail();
    }

    private function getSerializer(string $format): SchematicSerializerInterface
    {
        return match ($format) {
            'schematic' => new SchematicSerializer(),
            default => new SchemSerializer(),
        };
    }
}

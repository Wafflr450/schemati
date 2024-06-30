<?php

namespace App\Services\SchematicSerializers;

class SchemSerializer implements SchematicSerializerInterface
{
    public function serialize($schematicData): string
    {
        // If already GZIP-compressed, return as is
        if (substr($schematicData, 0, 2) === "\x1f\x8b") {
            return $schematicData;
        }
        // Otherwise, compress it
        return gzencode($schematicData);
    }

    public function getFileExtension(): string
    {
        return 'schem';
    }

    public function getMimeType(): string
    {
        return 'application/octet-stream';
    }
}

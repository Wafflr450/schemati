<?php

namespace App\Services\SchematicSerializers;

class SchematicSerializer implements SchematicSerializerInterface
{
    public function serialize($schematicData): string
    {
        // If GZIP-compressed, decompress it
        if (substr($schematicData, 0, 2) === "\x1f\x8b") {
            return gzdecode($schematicData);
        }
        // Otherwise, return as is
        return $schematicData;
    }

    public function getFileExtension(): string
    {
        return 'schematic';
    }

    public function getMimeType(): string
    {
        return 'application/octet-stream';
    }
}

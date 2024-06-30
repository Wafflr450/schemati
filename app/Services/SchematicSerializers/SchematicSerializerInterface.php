<?php

namespace App\Services\SchematicSerializers;

interface SchematicSerializerInterface
{
    public function serialize($schematicData): string;
    public function getFileExtension(): string;
    public function getMimeType(): string;
}

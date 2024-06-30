<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\CommonUtils;
use App\Models\Schematic;

class SchematicIdRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidSchematicId($value)) {
            $fail('The :attribute must be a valid UUID, short ID, or slug for an existing schematic.');
        }
    }

    private function isValidSchematicId($value): bool
    {
        if (CommonUtils::isUUID($value)) {
            return Schematic::where('id', $value)->exists();
        }

        if (strlen($value) >= 6 && preg_match('/^[a-zA-Z0-9-_]+$/', $value)) {
            return Schematic::where('short_id', $value)->exists();
        }

        if (preg_match('/^[a-z0-9-]+$/i', $value)) {
            return Schematic::where('slug', $value)->exists();
        }

        return false;
    }
}

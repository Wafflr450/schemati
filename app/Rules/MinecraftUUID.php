<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\MinecraftAPI;
use App\Utils\CommonUtils;
class MinecraftUUID implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!CommonUtils::isUUID($value)) {
            $fail("The $attribute must be a valid UUID.");
        }
        if (MinecraftAPI::getUsername($value) === null) {
            $fail("The $attribute must be a valid Minecraft UUID.");
        }
        return;
    }
}

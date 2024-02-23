<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\MinecraftAPI;

class MinecraftPlayerUUID implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (MinecraftAPI::getUsername($value) === null) {
            $fail('The :attribute must be a valid Minecraft player UUID.');
        }
    }
}

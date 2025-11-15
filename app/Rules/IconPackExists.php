<?php

namespace App\Rules;

use App\Facades\Icons;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IconPackExists implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        try {
            $iconPacks      = Icons::getIconPacks();
            $IconPackExists = $iconPacks->contains($value);

            if (! $IconPackExists) {
                $fail('validation.IconPackExists')->translate();
            }
        } catch (\Throwable $ex) {
            $fail('validation.IconPackExists')->translate();
        }
    }
}

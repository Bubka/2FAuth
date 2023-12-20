<?php

namespace App\Rules;

use App\Helpers\Helpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ParagonIE\ConstantTime\Base32;

class IsBase32Encoded implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        try {
            $secret = Base32::decodeUpper(Helpers::PadToBase32Format($value));
        } catch (\Exception $e) {
            $fail('validation.custom.secret.isBase32Encoded')->translate();
        }
    }
}

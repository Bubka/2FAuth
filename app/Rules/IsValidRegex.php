<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsValidRegex implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        try {
            preg_match('/' . $value . '/', '');
	
            if (preg_last_error() !== PREG_NO_ERROR) {
                $fail('validation.IsValidRegex')->translate();
            }
        }
        catch (\Throwable $ex) {
            $fail('validation.IsValidRegex')->translate();
        }
    }
}

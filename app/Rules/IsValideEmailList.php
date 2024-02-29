<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class IsValideEmailList implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $emails = explode('|', $value);

        $pass = Validator::make(
            $emails,
            [
                '*' => 'email',
            ]
        )->passes();

        if (! $pass) {
            $fail('validation.custom.email.IsValidEmailList')->translate();
        }
    }
}

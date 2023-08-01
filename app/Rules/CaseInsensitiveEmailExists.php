<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CaseInsensitiveEmailExists implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = DB::table('users')
        ->whereRaw('email = ?' . ('sqlite' === config('database.default') ? ' COLLATE NOCASE' : ''), [strtolower($value)])
        ->first();

        if (! $user) {
            $fail('validation.custom.email.exists')->translate();
        }
    }
}

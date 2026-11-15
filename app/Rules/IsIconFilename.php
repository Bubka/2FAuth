<?php

namespace App\Rules;

use App\Services\IconService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsIconFilename implements ValidationRule
{
    /**
     * Whether the given value is a valid icon filename
     */
    public static function isValid(mixed $value) : bool
    {
        // A bare name (no directory part) with a supported extension
        $pattern = '/^[A-Za-z0-9_-]+\.(' . implode('|', IconService::SUPPORTED_FILE_EXTENSIONS) . ')$/i';

        return is_string($value) && preg_match($pattern, $value) === 1;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (! self::isValid($value)) {
            $fail('validation.regex')->translate();
        }
    }
}

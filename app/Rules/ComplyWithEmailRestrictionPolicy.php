<?php

namespace App\Rules;

use App\Facades\Settings;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ComplyWithEmailRestrictionPolicy implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $list  = Settings::get('restrictList');
        $regex = Settings::get('restrictRule');
        
        $validatesFilter = true;
        $validatesRegex  = true;

        if (Settings::get('restrictRegistration') == true) {
            if ($list && ! in_array($value, explode('|', $list))) {
                $validatesFilter = false;
            }
            if ($regex && ! preg_match('/' . $regex . '/', $value)) {
                $validatesRegex = false;
            }

            if ($list && $regex) {
                if (! $validatesFilter && ! $validatesRegex) {
                    $fail('validation.custom.email.ComplyWithEmailRestrictionPolicy')->translate();
                }
            }
            else {
                if (! $validatesFilter || ! $validatesRegex) {
                    $fail('validation.custom.email.ComplyWithEmailRestrictionPolicy')->translate();
                }
            }
        }
    }
}

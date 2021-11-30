<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CaseInsensitiveEmailExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = DB::table('users')
            ->whereRaw('email = "' . strtolower($value) . '"' . ('sqlite' === config('database.default') ? ' COLLATE NOCASE' : ''))
            ->first();

        return !$user ? false : true;
    }

    /**
     * Get the validation error message.
     * @codeCoverageIgnore
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.email.exists');
    }
}

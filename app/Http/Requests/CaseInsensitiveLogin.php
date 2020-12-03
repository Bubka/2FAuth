<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class CaseInsensitiveLogin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {

                    if ('sqlite' === config('database.default')) {
                        $user = DB::table('users')
                         ->whereRaw('email = "' . $value . '" COLLATE NOCASE')
                        ->first();
                    }
                    else {
                        $user = DB::table('users')
                         ->where('email', $value)
                        ->first();
                    }

                    if (!$user) {
                        $fail(__('validation.custom.email.exists'));
                    }
                },
            ],
            'password' => 'required|string',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}

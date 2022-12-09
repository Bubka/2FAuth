<?php

namespace App\Api\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TwoFAccountUriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uri'        => 'required|string|regex:/^otpauth:\/\/[h,t]otp\//i',
            'custom_otp' => 'string|in:steamtotp',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'custom_otp' => strtolower($this->custom_otp),
        ]);
    }
}

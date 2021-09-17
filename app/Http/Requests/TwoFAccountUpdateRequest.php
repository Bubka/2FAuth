<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TwoFAccountUpdateRequest extends FormRequest
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
            'service' => 'required|nullable|string|regex:/^[^:]+$/i',
            'account' => 'required|string|regex:/^[^:]+$/i',
            'icon' => 'required|nullable|string',
            'otp_type' => 'required|string|in:totp,hotp',
            'secret' => ['required', 'string', 'bail', new \App\Rules\IsBase32Encoded],
            'digits' => 'required|integer|between:6,10',
            'algorithm' => 'required|string|in:sha1,sha256,sha512,md5',
            'period' => 'required_if:otp_type,totp|integer|min:1',
            'counter' => 'required_if:otp_type,hotp|integer|min:0',
        ];
    }
}

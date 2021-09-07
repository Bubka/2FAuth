<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TwoFAccountEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        // see https://github.com/google/google-authenticator/wiki/Key-Uri-Format
        // for otpauth uri format validation
        return [
            'service' => 'required_without:uri|string',
            'account' => 'required_without:uri|nullable|string|regex:/^[^:]+$/i',
            'icon' => 'nullable|string',
            'uri' => 'nullable|string|regex:/^otpauth:\/\/[h,t]otp\//i',
            'otpType' => 'required_without:uri|in:totp,hotp',
            'secret' => 'required_without:uri|string',
            'digits' => 'nullable|integer|between:6,10',
            'algorithm' => 'nullable|in:sha1,sha256,sha512,md5',
            'period' => 'required_if:otpType,totp|nullable|integer|min:1',
            'counter' => 'required_if:otpType,hotp|nullable|integer|min:0',
        ];
    }
}

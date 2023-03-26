<?php

namespace App\Http\Requests;

use Laragear\WebAuthn\Http\Requests\AssertedRequest;

class WebauthnAssertedRequest extends AssertedRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return array_merge(
            [
                'email' => 'required|email',
            ],
            parent::rules()
        );
    }
}

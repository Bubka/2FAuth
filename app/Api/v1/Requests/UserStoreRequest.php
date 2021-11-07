<?php

namespace App\Api\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name'      => [new \App\Rules\FirstUser, 'required', 'string', 'max:255'],
            'email'     => 'required|string|email|max:255',
            'password'  => 'required|string|min:8|confirmed',
        ];
    }
}
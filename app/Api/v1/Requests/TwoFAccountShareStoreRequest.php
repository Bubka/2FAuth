<?php

namespace App\Api\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TwoFAccountShareStoreRequest extends FormRequest
{
    /**
     * Normalize legacy payload using user_id to a list of ids.
     */
    protected function prepareForValidation() : void
    {
        if ($this->filled('user_id') && ! $this->has('ids')) {
            $this->merge([
                'ids' => [(int) $this->input('user_id')],
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            'ids'   => 'required|array|min:1',
            'ids.*' => 'required|integer|distinct|exists:users,id',
        ];
    }
}

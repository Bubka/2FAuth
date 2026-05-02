<?php

namespace App\Api\v1\Requests;

use App\Models\TwoFAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class TwoFAccountTransferOwnershipRequest extends FormRequest
{
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
            'new_owner_id'     => 'required|integer|exists:users,id',
            'confirm_password' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator) : void
    {
        $validator->after(function (Validator $validator) {
            /** @var TwoFAccount|null $twofaccount */
            $twofaccount = $this->route('twofaccount');

            if (! $twofaccount) {
                return;
            }

            if ((int) $this->input('new_owner_id') === (int) $twofaccount->user_id) {
                $validator->errors()->add('new_owner_id', __('validation.not_current_owner'));
            }
        });
    }
}

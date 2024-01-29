<?php

namespace App\Api\v1\Requests;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Auth;

class UserManagerStoreRequest extends UserStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdministrator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'is_admin' => 'required|boolean',
            ],
        );
    }
}

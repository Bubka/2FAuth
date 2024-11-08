<?php

namespace App\Api\v1\Requests;

use Illuminate\Support\Facades\Auth;

class TwoFAccountExportRequest extends TwoFAccountBatchRequest
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
        return array_merge(
            parent::rules(),
            [
                'otpauth' => 'sometimes|required|boolean',
            ],
        );
    }
}

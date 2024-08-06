<?php

namespace App\Api\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;

class TwoFAccountDynamicRequest extends FormRequest
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
        $rules = Arr::has($this->validationData(), 'uri')
            ? (new TwoFAccountUriRequest)->rules()
            : (new TwoFAccountStoreRequest)->rules();

        return $rules;
    }

    /**
     * Get the "withValidator" validation callables for the request.
     */
    public function withValidator(Validator $validator) : void
    {
        // The account may have to be assign to a specific group.
        // If so, we check if the provided group exists.
        $validator->sometimes('group_id', 'exists:groups,id', function (Fluent $input) {
            return $input['group_id'] > 0;
        });
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
            'otp_type'  => strtolower($this->otp_type),
            'algorithm' => strtolower($this->algorithm),
        ]);

        if ($this->has('group_id') && $this->group_id === '') {
            $this->merge([
                'group_id' => null,
            ]);
        }
    }
}

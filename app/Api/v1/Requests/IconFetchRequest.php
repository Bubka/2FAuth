<?php

namespace App\Api\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class IconFetchRequest extends FormRequest
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
        $rules = [
            'service'  => 'string',
            'iconPack' => [
                'sometimes',
                'required_without:iconCollection',
                'string',
                new \App\Rules\IconPackExists,
            ],
            'iconCollection' => 'sometimes|required_without:iconPack|string|in:tfa,selfh,dashboardicons',
            'variant'        => [
                'sometimes',
                'required_with:iconCollection',
                'missing_with:iconPack',
                'string',
            ],
        ];

        if ($this->input('iconCollection', null) === 'selfh') {
            $rules['variant'][] = 'in:regular,light,dark';
        }

        if ($this->input('iconCollection', null) === 'dashboardicons') {
            $rules['variant'][] = 'in:regular,light,dark';
        }

        if ($this->input('iconCollection', null) === 'tfa') {
            $rules['variant'][] = 'in:regular';
        }

        return $rules;
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
            'service' => strip_tags(strval($this->input('service'))),
        ]);
    }
}

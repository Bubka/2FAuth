<?php

namespace App\Api\v1\Requests;

use App\Rules\IsValidEmailList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SettingUpdateRequest extends FormRequest
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
        $rule = [
            'value' => [
                'required',
            ],
        ];

        if ($this->route()?->parameter('settingName') == 'restrictList') {
            $rule['value'][] = new IsValidEmailList;
        }

        return $rule;
    }
}

<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PopUpdateRequest extends FormRequest
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
        $rules = [
            'pop_channel_code_new'    => 'required',
            //'pop_rename' => 'min:3',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'pop_channel_code_new'    => 'Channel',
            //'pop_rename' => 'Pop Rename',
        ];
    }
}

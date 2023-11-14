<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class AccountInfoUpdateRequest extends FormRequest
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
        //$userId = \Request::segment(4);
//        dd($userId);

        $rules = [
            'name' => 'required|min:3',
            //'email'   => 'required|email|unique:users,id,' . $userId,
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'Name'
        ];
    }
}

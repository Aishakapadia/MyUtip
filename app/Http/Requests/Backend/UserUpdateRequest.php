<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = \Request::segment(4);
//        dd($userId);

        $rules = [
            'role_id' => 'required',
            'name'    => 'required|min:3',
            'email'   => 'required|email|unique:users,id,' . $userId,
        ];
        if (\Request::input('password')) {
            $rules = ['password' => 'min:5'];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'role_id'  => 'Role',
            'name'     => 'Name',
            'email'    => 'Email',
            'password' => 'Password',
            //'sort'     => 'Sort',
        ];
    }
}

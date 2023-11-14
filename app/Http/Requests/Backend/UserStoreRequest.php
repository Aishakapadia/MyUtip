<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserStoreRequest extends FormRequest
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

//        if (Request::has('transporter')) {
//            dump(Request::all());
//        }

        $rules = [
            'role_id'  => 'required',
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:5',
            //'sort'     => 'required|numeric',
        ];

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

<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class AccountPasswordUpdateRequest extends FormRequest
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
        return [
            'password_current'      => 'required',
            'password'              => 'required|min:5|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'password_current'      => 'Current Password',
            'password'              => 'New Password',
            'password_confirmation' => 'Confirm New Password',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        // checks user current password
        // before making changes
        $validator->after(function ($validator) {
            if ( !\Hash::check($this->password_current, $this->user()->password) ) {
                $validator->errors()->add('password_current', 'Your current password is incorrect.');
            }
        });
        return;
    }
}

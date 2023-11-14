<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
            'title'  => 'required|unique:roles',
            'slug'   => 'required',
            'active' => 'required|numeric',
            'sort'   => 'required|numeric',
        ];
    }

    /**
     * Validation errors display titles.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title'  => 'Title',
            'slug'   => 'Slug',
            'active' => 'Active',
            'sort'   => 'Sort',
        ];
    }
}

<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
        $id = \Request::segment(4);
        return [
            'title'  => 'required|unique:roles,id,' . $id,
            'slug'   => 'required',
            'active' => 'required|numeric',
            'sort'   => 'required|numeric',
        ];
    }

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

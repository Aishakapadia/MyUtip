<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SiteStoreRequest extends FormRequest
{
    /**
     * Determine if the page is authorized to make this request.
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
            'title'         => 'required|min:3',
            //'slug'          => 'required|unique:sites',
            'site_type_id'  => 'required',
            'material_type' => 'required',
            'sort'          => 'required|numeric',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'         => 'Title',
            //'slug'          => 'Slug',
            'site_type_id'  => 'Site Type',
            'material_type' => 'Material Type',
            'sort'          => 'Sort',
        ];
    }
}

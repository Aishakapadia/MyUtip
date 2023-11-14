<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class MaterialStoreRequest extends FormRequest
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
            'title'    => 'required|min:3',
            'sap_code' => 'required|unique:materials',
            'sort'     => 'required|numeric',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'    => 'Title',
            'sap_code' => 'Code',
            'sort'     => 'Sort',
        ];
    }
}

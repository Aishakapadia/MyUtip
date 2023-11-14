<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'   => 'required|min:3',
            //'slug'    => 'required|unique:pages,id,' . $id,
            'content' => 'required|min:10',
            'sort'    => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'title'   => 'Title',
            'slug'    => 'Slug',
            'content' => 'Content',
            'sort'    => 'Sort',
        ];
    }
}

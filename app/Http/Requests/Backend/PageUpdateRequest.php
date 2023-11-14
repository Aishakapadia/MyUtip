<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
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
        $id = \Request::segment(4);

        return [
            'title'    => 'required|min:3',
            'slug'     => 'required|unique:pages,id,' . $id,
            'contents' => 'required|min:10',
            'sort'     => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'title'    => 'Title',
            'slug'     => 'Slug',
            'contents' => 'Content',
            'sort'     => 'Sort',
        ];
    }
}

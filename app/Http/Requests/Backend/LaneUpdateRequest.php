<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class LaneUpdateRequest extends FormRequest
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
        $rules = [
            'title'                 => 'required|min:3',
//            'slug'                  => 'required|unique:pages',
            'site_id_from'          => 'required',
            'site_id_to'            => 'required',
            'lane_transporter_list' => 'required',
            'sort'                  => 'required|numeric',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'                 => 'Title',
//            'slug'                  => 'Slug',
            'site_id_from'          => 'Site From',
            'site_id_to'            => 'Site To',
            'lane_transporter_list' => 'Transporters',
            'sort'                  => 'Sort',
        ];
    }
}

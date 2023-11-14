<?php

namespace App\Http\Requests\Backend;

use App\Rules\LaneCheck;
use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
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
            'vehicle_type_id'     => 'required',
            'site_id_from'        => 'required',
            'site_id_to'          => ['required', new LaneCheck],
            'material_type.*'     => 'required',
            'material_id.*'       => 'required',
            'unit_id.*'           => 'required',
            'quantity.*'          => 'required',
            'weight.*'            => 'required',
            'po_number.*'         => 'required',
            'vehicle_required_at' => 'required',
        ];

        return $rules;
    }

//    public function attributes()
//    {
//        return [
//            'remarks' => 'Remarks',
//        ];
//    }

    public function messages()
    {
        return [
            'vehicle_type_id.required'     => 'The Vehicle Type field is required.',
            'site_id_from.required'        => 'The Site From field is required.',
            'site_id_to.required'          => 'The Site To field is required.',
            'material_id.*.required'       => 'The Material Code field is required.',
            'material_type.*.required'     => 'The Material Type field is required.',
            'unit_id.*.required'           => 'The Unit field is required.',
            'quantity.*.required'          => 'The Quantity field is required.',
            'weight.*.required'            => 'The Weight field is required.',
            'po_number.*.required'         => 'The PO Number field is required.',
            'vehicle_required_at.required' => 'The Vehicle required at field is required.',
            //'delivery_challan_number.required' => 'The Delivery Challan field is required.',
            //'remarks.required'                 => 'The Remarks field is required.',
        ];
    }
}

<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TicketStatusUpdateRequest extends FormRequest
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
        //dd(Request::all());
        $rules = [
            'status_id' => 'required',
            //'comments'  => '',
        ];

//        if (\Auth::user()->role_id == \Config::get('constants.ROLE_ID_ADMIN') && Request::get('current_status') == \Config::get('constants.ACCEPT_BY_TRANSPORTER')) {
//            $rules['transporter_id'] = 'required';
//        }
//
//        if (\Auth::user()->role_id == \Config::get('constants.ROLE_ID_TRANSPORTER') && Request::get('current_status') == \Config::get('constants.APPROVE_BY_ADMIN')) {
//            $rules['vehicle_number'] = 'required';
//            $rules['driver_contact'] = 'required';
//            $rules['eta'] = 'required';
//        }

        $authUser = \Auth::user();
        $currentStatus = Request::get('current_status');

        switch ($authUser->role_id) {
            case \Config::get('constants.ROLE_ID_SUPPLIER'):

                if ($currentStatus == \Config::get('constants.VEHICLE_ARRIVED_BY_TRANSPORTER')) {
                    $rules['delivery_challan_number'] = 'required';
                }

                break;

            case \Config::get('constants.ROLE_ID_ADMIN'):

                if ($currentStatus == \Config::get('constants.ACCEPT_BY_TRANSPORTER')) {
                    $rules['transporter_id'] = 'required';
                }

                break;

            case \Config::get('constants.ROLE_ID_TRANSPORTER'):

                if ($currentStatus == \Config::get('constants.APPROVE_BY_ADMIN')) {
                    $rules['vehicle_number'] = 'required';
                    $rules['driver_contact'] = 'required';
                    $rules['eta'] = 'required';
                }

                break;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'status_id'      => 'Status',
            'transporter_id' => 'Transporter',
            'vehicle_number' => 'Vehicle Number',
            'driver_contact' => 'Driver Contact Number',
            'eta'            => 'Estimated Time Arrival',
            //'comments'  => 'Comments',
        ];
    }
}

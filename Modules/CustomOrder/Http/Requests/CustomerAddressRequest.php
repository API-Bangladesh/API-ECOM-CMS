<?php

namespace Modules\CustomOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Customer table fields
            'name' => 'required',
            'password' => 'required',
            'gender' => 'nullable',
            'phone_number' => 'required',
            'date_of_birth' => 'nullable',

            // Address table fields
            'title' => 'required',
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

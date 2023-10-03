<?php

namespace App\Http\Requests;

class GeneralSettingFormRequest extends FormRequest
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
            'title'             => 'required|string',
            'address'           => 'required|string',
            'copyright'         => 'required|string',
            'email'             => 'required|string',
            'phone'             => 'required|string',
            'hotnumber'         => 'required|string',
            'logo'              => 'nullable|image|mimes:png',
            'favicon'           => 'nullable|image|mimes:png',
            'footerlogo'        => 'nullable|image|mimes:png',
        ];
    }
}

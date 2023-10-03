<?php

namespace Modules\Location\Http\Requests;

use App\Http\Requests\FormRequest;

class LocationFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required'];
        $rules['retail_code'] = ['required','string','unique:locations,name'];
        if(request()->update_id){
            $rules['retail_code'][2] = 'unique:locations,retail_code,'.request()->update_id;
        }
        $rules['owner_name']       = ['nullable'];
        $rules['postal_code']       = ['nullable'];
        $rules['address']       = ['nullable'];
        $rules['zone']       = ['nullable'];
        $rules['sales_person']       = ['nullable'];
        $rules['phone']       = ['nullable'];
        $rules['division']       = ['nullable'];
        $rules['district']       = ['nullable'];
        $rules['lat']       = ['nullable'];
        $rules['long']       = ['nullable'];
        return $rules;
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

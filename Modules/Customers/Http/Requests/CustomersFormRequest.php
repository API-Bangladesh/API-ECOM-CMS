<?php

namespace Modules\Customers\Http\Requests;

use App\Http\Requests\FormRequest;

class CustomersFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:content_types,name'];
        $rules['address'] = ['required','string','unique:content_types,name'];

        if(request()->update_id){
            $rules['name'][2] = 'unique:customers,name,'.request()->update_id;
            $rules['address'][2] = 'unique:customers,address,'.request()->update_id;
        }
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

<?php

namespace Modules\VariantOption\Http\Requests;

use App\Http\Requests\FormRequest;

class VariantOptionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:variant_options,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:variant_options,name,'.request()->update_id;
        }
        $rules['variant_id'] = ['required'];
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

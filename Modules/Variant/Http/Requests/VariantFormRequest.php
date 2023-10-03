<?php

namespace Modules\Variant\Http\Requests;

use App\Http\Requests\FormRequest;

class VariantFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:variants,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:variants,name,'.request()->update_id;
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


<?php

namespace Modules\SubCategory\Http\Requests;

use App\Http\Requests\FormRequest;

class SubCategoryFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:sub_categories,name'];
        $rules['category_id']       = ['required'];
        $rules['sub_category_description']       = ['nullable'];
        $rules['image']  = ['nullable','image','mimes:png,jpg'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:sub_categories,name,'.request()->update_id;
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

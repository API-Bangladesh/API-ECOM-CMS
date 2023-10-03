<?php

namespace Modules\ComboCategory\Http\Requests;

use App\Http\Requests\FormRequest;

class ComboCategoryFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:categories,name'];
        $rules['image']  = ['nullable','image','mimes:png,jpg'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:combo_categories,name,'.request()->update_id;
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

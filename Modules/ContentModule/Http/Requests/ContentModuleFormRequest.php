<?php

namespace Modules\ContentModule\Http\Requests;

use App\Http\Requests\FormRequest;

class ContentModuleFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:content_modules,name'];
        $rules['module_description']  = ['nullable'];
        $rules['image']  = ['nullable','image','mimes:png,jpg'];
        $rules['module_color']  = ['nullable'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:content_modules,name,'.request()->update_id;
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

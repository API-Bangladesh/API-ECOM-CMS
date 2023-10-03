<?php

namespace Modules\Picture\Http\Requests;

use App\Http\Requests\FormRequest;

class PictureFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['nullable','string'];
        $rules['gallery_category_id'] = ['required'];
        $rules['image']  = ['nullable','image','mimes:png,jpg'];
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

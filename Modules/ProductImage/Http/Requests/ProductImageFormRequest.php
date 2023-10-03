<?php

namespace Modules\ProductImage\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductImageFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['product_id'] = ['nullable'];
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

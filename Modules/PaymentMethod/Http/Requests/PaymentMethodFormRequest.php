<?php

namespace Modules\PaymentMethod\Http\Requests;

use App\Http\Requests\FormRequest;

class PaymentMethodFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['code'] = ['required','string','unique:payment_methods,code'];
        $rules['name'] = ['required','string','unique:payment_methods,code'];
        $rules['image']  = ['nullable','image','mimes:png,jpg'];
        if(request()->update_id){
            $rules['code'][2] = 'unique:payment_methods,code,'.request()->update_id;
            $rules['name'][2] = 'unique:payment_methods,name,'.request()->update_id;
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

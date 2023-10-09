<?php

namespace Modules\CustomOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['order_text']       = ['required'];
        $rules['media']       = ['required'];
        $rules['page_id']       = ['required'];
        $rules['date_time']       = ['nullable'];
        $rules['info']       = ['nullable'];

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

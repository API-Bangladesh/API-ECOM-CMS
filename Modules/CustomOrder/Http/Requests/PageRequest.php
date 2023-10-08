<?php

namespace Modules\CustomOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if (request()->has('update_id')) {
            $rules['page'][] = ['required','unique:pages,page,' . request()->update_id];
        }else{
            $rules['page']       = ['required', 'unique:pages,page'];
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

<?php


namespace Modules\Combo\Http\Requests;

use App\Http\Requests\FormRequest;

class ComboImageFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['combo_id'] = ['required'];
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

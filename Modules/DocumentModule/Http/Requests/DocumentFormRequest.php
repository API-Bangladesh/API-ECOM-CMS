<?php

namespace Modules\DocumentModule\Http\Requests;
use App\Http\Requests\FormRequest;


class DocumentFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules                      = [];
        $rules['name']            = ['required','string'];
        $rules['image']             = ['nullable','image','mimes:png,jpg'];
        $rules['document_category_id'] = ['required'];
        $rules['document_count']    = ['nullable'];
        $rules['document_file']     = ['nullable'];
        $rules['document_desc']     = ['nullable'];
	$rules['document_order']     = ['nullable'];

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

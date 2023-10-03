<?php

namespace Modules\ContentItem\Http\Requests;

use App\Http\Requests\FormRequest;


class ContentItemFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules                      = [];
        $rules['name']              = ['required','string'];
        $rules['image']             = ['nullable','image','mimes:png,jpg'];
        $rules['item_image_banner'] = ['nullable','image','mimes:png,jpg'];
        $rules['category_id']       = ['required'];
        $rules['type_id']           = ['required'];
        $rules['module_id']         = ['required'];
        $rules['item_link']         = ['nullable'];
        $rules['item_video_link']   = ['nullable'];
        $rules['item_date']         = ['nullable'];
        $rules['item_short_desc']   = ['nullable'];
        $rules['item_long_desc']    = ['nullable'];
	$rules['item_order']    = ['nullable'];

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

<?php

namespace Modules\Blog\Http\Requests;

use App\Http\Requests\FormRequest;


class BlogFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules                      = [];
        $rules['name']              = ['required','string','unique:blogs,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:blogs,name,'.request()->update_id;
        }
        $rules['image']             = ['nullable','image','mimes:png,jpg,jpeg'];
        $rules['blog_banner_image']   = ['nullable','image','mimes:png,jpg,jpeg'];
        $rules['blog_category_id']       = ['required'];
        $rules['blog_author']      = ['nullable'];
        $rules['blog_date']      = ['nullable','date'];
        $rules['blog_short_desc'] = ['nullable'];
        $rules['blog_long_desc'] = ['nullable'];
	$rules['blog_order'] = ['nullable'];
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

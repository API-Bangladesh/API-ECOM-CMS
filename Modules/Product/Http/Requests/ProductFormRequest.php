<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\FormRequest;


class ProductFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules                      = [];
        $rules['name']              = ['required','string','unique:products,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:products,name,'.request()->update_id;
        }
        $rules['image']             = ['nullable','image','mimes:png,jpg,jpeg'];
        $rules['lifestyle_image']   = ['nullable','image','mimes:png,jpg,jpeg'];
        $rules['category_id']       = ['required'];
        $rules['sub_category_id']   = ['nullable'];
        $rules['variant_id']        = ['nullable'];
        $rules['variant_option_id'] = ['nullable'];
        $rules['segment_id']        = ['nullable'];
        $rules['pack_id']           = ['nullable'];
 	$rules['product_order']     = ['nullable'];
        $rules['product_link']      = ['nullable'];
        $rules['product_bncn']      = ['nullable'];
        $rules['product_short_desc'] = ['nullable'];
        $rules['product_long_desc'] = ['nullable'];
        $rules['product_video_path'] = ['nullable','file','mimes:mp4,ogx,oga,ogv,ogg,webm'];
        $rules['product_brochure'] = ['nullable','file'];

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

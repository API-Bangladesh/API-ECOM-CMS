<?php

namespace Modules\Video\Http\Requests;

use App\Http\Requests\FormRequest;

class VideoFormRequest extends FormRequest
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
        $rules['video_link'] = ['nullable','string'];
        $rules['video']  = ['nullable','file','mimes:mp4,ogx,oga,ogv,ogg,webm'];
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

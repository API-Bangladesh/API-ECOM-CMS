<?php

namespace Modules\Contact\Http\Requests;

use App\Http\Requests\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:contacts,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:contacts,name,'.request()->update_id;
        }
        $rules['contact_address']   = ['nullable'];
        $rules['contact_email']   = ['nullable'];
        $rules['contact_phone']   = ['nullable'];
        $rules['contact_map_key'] = ['nullable'];
        $rules['contact_link']       = ['nullable'];
        $rules['contact_longitude']  = ['nullable'];
        $rules['contact_latitude']   = ['nullable'];

        return $rules;
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


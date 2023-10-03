<?php

namespace Modules\MailSettings\Http\Requests;

use App\Http\Requests\FormRequest;

class MailSettingsFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:mail_settings,name'];
        $rules['mail_address'] = ['required','string','unique:mail_settings,mail_address'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:mail_settings,name,'.request()->update_id;
            $rules['mail_address'][2] = 'unique:mail_settings,mail_address,'.request()->update_id;
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


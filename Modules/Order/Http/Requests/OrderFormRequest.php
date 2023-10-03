<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FormRequest;

class OrderFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['shipping_address'] = ['required'];
        $rules['billing_address']       = ['required'];

        if(request()->update_id){
              $rules['shipping_address'][2] = ['required'];
              $rules['billing_address'][2]  = ['nullable'];
              $rules['shipping_charge'][2]  = ['nullable'];
              $rules['payment_method_id'][2] = ['required'];
              $rules['payment_details'][2] = ['nullable'];
              $rules['payment_status_id'][2] = ['nullable'];
              $rules['discount'][2] = ['nullable'];
              $rules['total'][2] = ['nullable'];
              $rules['tax'][2]  = ['nullable'];
              $rules['grand_total'][2]  = ['nullable'];
//            $rules['shipping_address'][2] = 'shipping_address,'.request()->update_id;
//            $rules['billing_address'][2] = 'billing_address,'.request()->update_id;
//            $rules['shipping_charge'][2] = 'shipping_charge,'.request()->update_id;
//            $rules['payment_method_id'][2] = 'payment_method_id,'.request()->update_id;
//            $rules['payment_details'][2] = 'payment_details,'.request()->update_id;
//            $rules['payment_status_id'][2] = 'payment_status_id,'.request()->update_id;
//            $rules['total'][2] = 'total'.request()->update_id;
//            $rules['tax'][2] = 'tax'.request()->update_id;
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

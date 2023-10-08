<?php

namespace Modules\CustomOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomOrderMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

//        if (request()->has('update_id')) {
//            $rules['title'][] = 'unique:combos,title,' . request()->update_id;
//        }else{
//            $rules['title']       = ['required', 'unique:combos,title'];
//        }
        $rules['order_date']       = ['required'];
        $rules['customer_id']       = ['required'];
        $rules['shipping_address']       = ['required'];
        $rules['shipping_address_json']       = ['nullable'];
        $rules['billing_address']       = ['required'];
        $rules['billing_address_json']       = ['nullable'];
        $rules['total']       = ['required'];
        $rules['discount']       = ['nullable'];
        $rules['shipping_charge']       = ['nullable'];
        $rules['special_note']       = ['nullable'];
        $rules['grand_total']       = ['nullable'];
        $rules['tax']       = ['nullable'];
        $rules['media']       = ['nullable'];
        $rules['order_message_id']       = ['nullable'];
        $rules['payment_method_id']       = ['nullable'];
        $rules['ecourier_details_json']       = ['nullable'];
        $rules['payment_details']       = ['nullable'];
        $rules['payment_status_id']       = ['nullable'];
        $rules['ecourier_tracking']       = ['nullable'];
        $rules['order_status_id']       = ['nullable'];

        //order item
        $rules['type']       = ['nullable'];
        $rules['order_id']       = ['nullable'];
        $rules['inventory_id']       = ['nullable'];
        $rules['combo_id']       = ['nullable'];
        $rules['quantity']       = ['nullable'];
        $rules['unit_price']       = ['nullable'];

        $numQntys = count($this->quantity);

        for($n=0;$n<$numQntys;$n++) {
            if($this->quantity[$n]) continue;
            $rules['quantity-' . $n] = 'required';
        }

        $numInventory_ids = count($this->inventory_id);

        for($j=0;$j<$numInventory_ids;$j++) {
            if($this->inventory_id[$j]) continue;
            $rules['inventory_id-' . $j] = 'required';
        }

        $unit_price = count($this->unit_price);
        for($k=0;$k<$unit_price;$k++) {
            if($this->unit_price[$k]) continue;
            $rules['unit_price-' . $k] = 'required';
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

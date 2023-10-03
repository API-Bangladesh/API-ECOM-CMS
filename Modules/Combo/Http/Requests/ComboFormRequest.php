<?php

namespace Modules\Combo\Http\Requests;

use App\Http\Requests\FormRequest;

class ComboFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['combo_category_id'] = ['required'];
        $rules['title']       = ['required'];
        if (request()->has('update_id')) {
            $rules['title'][] = 'unique:combos,title,' . request()->update_id;
        }else{
            $rules['title']       = ['required', 'unique:combos,title'];
        }
        $rules['product_short_desc']       = ['nullable'];
        $rules['product_long_desc']       = ['nullable'];
        $rules['sale_price']       = ['nullable'];
        $rules['offer_price']       = ['nullable'];
        $rules['offer_start']       = ['nullable'];
        $rules['offer_end']       = ['nullable'];
        $rules['stock_quantity']       = ['nullable'];
        $rules['reorder_quantity']       = ['nullable'];
        $rules['is_special_deal']       = ['nullable'];
        $rules['is_manage_stock']       = ['nullable'];
        $rules['min_order_quantity']       = ['nullable'];

        $numQntys = count($this->quantity);

        for($n=0;$n<$numQntys;$n++) {
            $rules['quantity.' . $n] = 'required';
        }

        $numInventory_ids = count($this->inventory_id);

        for($j=0;$j<$numInventory_ids;$j++) {
            $rules['inventory_id.' . $j] = 'required';
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

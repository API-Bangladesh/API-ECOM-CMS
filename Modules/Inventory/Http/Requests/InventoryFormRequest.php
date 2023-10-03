<?php

namespace Modules\Inventory\Http\Requests;

use App\Http\Requests\FormRequest;

class InventoryFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['product_id'] = ['required'];
        $rules['title'] = ['required'];
        $rules['variant_id'] = ['required', 'array'];
        $rules['sale_price']  = ['nullable'];
//        if(request()->update_id){
//            $rules['sku'][2] = 'unique:inventories,sku,' . request()->update_id;
//        }
//        else{
//            $rules['sku'] = ['required','unique:inventories,sku'];
//        }
        $rules['image']  = ['nullable','image','mimes:png,jpg,jpeg'];
        $rules['offer_price']  = ['nullable'];
        $rules['offer_start']  = ['nullable'];
        $rules['offer_end']  = ['nullable'];
        $rules['stock_quantity']  = ['nullable'];
        $rules['reorder_quantity']  = ['nullable'];
        $rules['min_order_quantity']  = ['nullable'];
        $rules['min_order_quantity']  = ['nullable'];
        $rules['is_special_deal']  = ['nullable'];
        $rules['is_manage_stock']  = ['nullable'];

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

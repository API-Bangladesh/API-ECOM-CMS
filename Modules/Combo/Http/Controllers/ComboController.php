<?php

namespace Modules\Combo\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Combo\Entities\ComboImage;
use Modules\ComboCategory\Entities\ComboCategory;
use Modules\Combo\Entities\Combo;
use Modules\Combo\Entities\ComboItem;
use Modules\Inventory\Entities\Inventory;
use Modules\Combo\Http\Requests\ComboFormRequest;
use DB;

class ComboController extends BaseController
{
    use UploadAble;

    public function __construct(Combo $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        // dd($this->model->getDatatableList());
        if (permission('combo-access')) {
            $this->setPageData('combo Product', 'combo Product', 'fas fa-box');
            $data = [
                'ComboCategories' => ComboCategory::all(),
                'Inventories' => Inventory::all(),
            ];
            return view('combo::index', $data);
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('combo-access')) {
            if ($request->ajax()) {
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->product_id)) {
                    $this->model->setProduct($request->product_id);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {

                    $no++;
                    $action = '';

                    if (permission('combo-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('combo-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->title . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];
                    if (permission('combo-bulk-delete')) {
//                        $row[] = table_checkbox($value->id);
                    }

                    $row[] = $no;
                    if($value->stock_quantity <= $value->reorder_quantity){
                        $row[] = '<span class="text-danger">'.$value->title.'</span>';
                    }else{
                        $row[] = $value->title;
                    }
                    $row[] = $value->sku;
                    $row[] = $value->sale_price;
                    $row[] = $value->offer_price;

                    $row[] = permission('combo-edit') ? change_status($value->id, $value->status, $value->title) : STATUS_LABEL[$value->status];

                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(),
                    $this->model->count_filtered(), $data);
            } else {
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(ComboFormRequest $request)
    {
        if ($request->ajax()) {
            if (permission('combo-add') || permission('combo-edit')) {
                $collection = collect($request->validated())->except(['combo_category_id', 'quantity']);
                $old_result = $this->model->find($request->update_id);
                $collection = $this->track_data($request->update_id, $collection);
                $combo_category_id = $request->combo_category_id;
                if($request->update_id==''){
                    $sku = self::getUniqueId($this->model);
                    $collection = $collection->merge(compact('sku'));
                }
                $collection = $collection->merge(compact('combo_category_id'));
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),Combo_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,Combo_IMAGE_PATH);
                    }
                }

                $lifestyle_image = $request->old_lifestyle_image;
                if($request->hasFile('lifestyle_image')){
                    $lifestyle_image = $this->upload_file($request->file('lifestyle_image'),COMBO_lIFESTYLE_IMAGE_PATH);

                    if(!empty($request->old_lifestyle_image)){
                        $this->delete_file($request->old_lifestyle_image,COMBO_lIFESTYLE_IMAGE_PATH);
                    }
                }
                if($request->is_manage_stock==null){
                    $is_manage_stock=2;
                    $collection = $collection->merge(compact('is_manage_stock'));
                }
                $collection = $collection->merge(compact('image','lifestyle_image'));

                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());

                //combo variant option save start
                $combo['combo_id'] = $result->id??0;
                $inventory_ids = $request->inventory_id;
                $quantity = $request->quantity;
                //combo stock quantity
                $combo_stock_quantity = abs($request->stock_quantity);
                if($combo_stock_quantity==0||$combo_stock_quantity==null||$combo_stock_quantity==''){
                    $combo_stock_quantity=1;
                }
                if(isset($request->inventory_id)){
                    for($j=0;$j<count($request->inventory_id);$j++){

                        //inventory is_manage stock and stock available quantity retrive
                        $item = ComboItem::where('combo_id',$request->update_id)->where('inventory_id',$inventory_ids[$j])->first();

                        $inventory_status = Inventory::find($inventory_ids[$j]);
                        $is_manage_stock = $inventory_status->is_manage_stock??0;

                        //inventory stock quantity
                        $inventory_stock_quantity = $inventory_status->stock_quantity??0;
                        if($inventory_stock_quantity<=0){
                            $inventory_stock_quantity=1;
                        }

                        //below i minus new request data - old db data if i got $combo_item_current_quantity = plus value
                        //then i minus this value from inventory table stock_quantity for - value i plus this value
                        if(isset($item->quantity)){
                            //$combo_stock_quantity = $request->stock_quantity;
                            // new request->quantity*request->stock_quantity-db->combo->stock_quantity*Combo_items->quantity;
                            $combo_item_current_quantity = abs($quantity[$j])*$combo_stock_quantity - $item->quantity*$old_result->stock_quantity??1;
                        }else{
                            $combo_item_current_quantity = abs($quantity[$j])*$combo_stock_quantity;
                        }

                        if (isset($item) && $item->count()>0) {

                            $item->combo_id = $request->update_id; // Set the 'combo_id' value
                            /*if is_manage_stock=1 then i plus quantity or minus quantity
                            if need to minus quantity then need to check after minus inventory table stock_quantity
                            it remain plus quantity or minus quantity. If remain minus quantity then return a validation error
                            quantity is not available
                            */
                            if($is_manage_stock==1){
                                //inventory item quantity minus inventory table stock_quantity at $inventory_stock_quantity
                                //new request - old saved data if get -value then i plus stock quantity
                                $result_cl = $quantity[$j]*$combo_stock_quantity-$item->quantity*$old_result->stock_quantity;
                                if($result_cl<0){
                                    $inventory_stock_data = $inventory_stock_quantity+abs($combo_item_current_quantity);
                                }elseif($result_cl>0){
                                    $inventory_stock_data = $inventory_stock_quantity-$combo_item_current_quantity;
                                }elseif(abs($quantity[$j])*$combo_stock_quantity==$item->quantity*$old_result->stock_quantity){
                                    $inventory_stock_data = $inventory_stock_quantity;
                                }else{
                                    $inventory_stock_data = $inventory_stock_quantity;
                                }

                                $inventory_update_stock_availability = $inventory_stock_quantity-$combo_item_current_quantity;

                                if($inventory_update_stock_availability<0){
//                                    return '1=>'.$inventory_update_stock_availability;
                                    $item->quantity = abs($quantity[$j]);
                                    $inventory_status = Inventory::find($inventory_ids[$j])->update(['stock_quantity'=>0]);
//                                    return response()->json(['code'=>404,'message'=>'Validation error item quantity is not available']);
                                }
                                else if($inventory_update_stock_availability>=0){
//                                    return '2=>'.$inventory_update_stock_availability;
                                    $item->quantity = abs($quantity[$j]);
                                    $inventory_status = Inventory::find($inventory_ids[$j])->update(['stock_quantity'=>$inventory_stock_data]);
                                }
                            }else{
                                $item->quantity = abs($quantity[$j]);
                            }

                        }else{

                            $item = new ComboItem();
                            // Update or insert new attributes
                            $item->inventory_id = $request->inventory_id[$j];
                            $item->combo_id = $result->id??0;
                            /*if is_manage_stock=1 that time insert only check if insert item_quantity is grater then
                              inventory item_quantity then show validation error else no checking and inventory
                             quantity minus is no need */
                            $inventory_item_availability = $inventory_stock_quantity-abs($quantity[$j])*$combo_stock_quantity;

                            if($is_manage_stock==1 && $inventory_item_availability>=0){
                                $inventory_status = Inventory::find($inventory_ids[$j])->update(['stock_quantity'=>$inventory_item_availability]);
                                $item->quantity = abs($quantity[$j]);
                            }else if($is_manage_stock==1 && $inventory_item_availability<0){
                                // inventory item quantity is not available
                                $item->quantity = abs($quantity[$j]);
                                $inventory_status = Inventory::find($inventory_ids[$j])->update(['stock_quantity'=>0]);
//                                return response()->json(['code'=>404,'message'=>'Validation error item quantity is not available']);
                            }
                            else{
                                $item->quantity = abs($quantity[$j]);
                            }

                            }
                            // Save the changes to the database
                            $item->save();
                    }
                    //previous item delete
                    $old_item = ComboItem::where('combo_id',$request->update_id)->whereNotIn('inventory_id',$inventory_ids)->delete();
                }

              $output = $this->store_message($result, $request->update_id);
              return response()->json($output);

            } else {
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            return response()->json($this->access_blocked());
        }
    }
    public static function getUniqueId(Combo $model) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[random_int(0, strlen($characters) - 1)];
        }
        if ($model->where('sku', $id)->exists()) {
            return self::getUniqueId($model);
        }
        return $id;
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('combo-edit')) {
                $data = $this->model->findOrFail($request->id);
                $data->load('inventoryComboItems');
                $data['all_inventory'] = Inventory::get();
                $output = $this->data_message($data);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('combo-delete')) {
                try{
                //combo get stock_quantity
                $combo_product = Combo::findOrFail($request->id);
                $combo_product_quantity = $combo_product->stock_quantity??1;

                //combo item quantity
                $combo_childs = ComboItem::where('combo_id',$request->id)->get();

                if(!$combo_childs->isEmpty()){
                foreach($combo_childs as $combo_child){
                    $combo_child_quantity =  $combo_child->quantity??0;
                    $combo_product_quantity = $combo_product->stock_quantity??1;
                    //update inventory quantity
                    $inventory = Inventory::findOrFail($combo_child->inventory_id);
                    if(isset($inventory->is_manage_stock) && $inventory->is_manage_stock==1){
                        $inventory_quantity = $inventory->stock_quantity??0;
                        $calculated_quantity = $combo_product_quantity*$combo_child_quantity;
                        $calculated_quantity_plus = $calculated_quantity+$inventory_quantity;

                        $inventory->update(['stock_quantity'=>$calculated_quantity_plus]);
                    }
                }
                }

                $combo_image = ComboImage::where('combo_id',$request->id)->delete();
                $combo_item = ComboItem::where('combo_id',$request->id)->delete();
                $combo = $this->model->find($request->id);
                $combo->delete();
                $output = $this->delete_message($combo);
                }catch(Exception $e){
                    return response()->json(['message'=>$e->getMessage()]);
                }
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

//    public function bulk_delete(Request $request)
//    {
//        if ($request->ajax()) {
//            if (permission('combo-bulk-delete')) {
//                $combo_item = ComboItem::whereIn('combo_id',$request->ids)->delete();
//                $result = $this->model->destroy($request->ids);
//                $output = $this->bulk_delete_message($result);
//            } else {
//                $output = $this->access_blocked();
//            }
//            return response()->json($output);
//        } else {
//            return response()->json($this->access_blocked());
//        }
//    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            if (permission('combo-edit')) {
                $result = $this->model->find($request->id)->update(['status' => $request->status]);
                $output = $result ? ['status' => 'success', 'message' => 'Status has been changed successfully']
                    : ['status' => 'error', 'message' => 'Failed to change status'];
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }
}

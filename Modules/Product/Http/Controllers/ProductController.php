<?php

namespace Modules\Product\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Category\Entities\Category;
use Modules\Combo\Entities\Combo;
use Modules\Inventory\Entities\Inventory;
use Modules\PackType\Entities\PackType;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\ProductFormRequest;
use Modules\Segment\Entities\Segment;
use Modules\SubCategory\Entities\SubCategory;
use Modules\Variant\Entities\Variant;
use Modules\VariantOption\Entities\VariantOption;

class ProductController extends BaseController
{
    use UploadAble;
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('product-access')){
            $this->setPageData('Manage Product','Manage Product','fas fa-box');
            $data = [
                'categories' => Category::all(),
                'variants' => Variant::all(),
                'segments' => Segment::all(),
                'packTypes' => PackType::all(),
            ];
            return view('product::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('product-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->category_id)) {
                    $this->model->setCategory($request->category_id);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('product-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('product-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('product-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('product-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
		    $row[] = $value->id;
		    $row[] = $value->product_order;
                    $row[] = table_image($value->image,PRODUCT_IMAGE_PATH,$value->name);
                    $row[] = table_image($value->lifestyle_image,PRODUCT_IMAGE_PATH,$value->name);
                    $row[] = $value->name;
                    $row[] = $value->category->name??'';
		    $row[] = $value->product_brochure;
                    $row[] = permission('product-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                 $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(ProductFormRequest $request)
    {
        if($request->ajax()){
            if(permission('product-add') || permission('product-edit')){
                $collection = collect($request->validated())->except(['image','product_video_path','lifestyle_image','product_brochure']);
                $product_video_path = $request->product_video_path;
                $collection = $this->track_data($request->update_id,$collection);
                $product_video_path = $request->old_product_video_path;
                $image = $request->old_image;
                $product_brochure = $request->product_brochure;
                $product_brochure = $request->old_product_brochure;
                $lifestyle_image = $request->old_lifestyle_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),PRODUCT_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,PRODUCT_IMAGE_PATH);
                    }
                }
                if($request->hasFile('lifestyle_image')){
                    $lifestyle_image = $this->upload_file($request->file('lifestyle_image'),PRODUCT_IMAGE_PATH);

                    if(!empty($request->old_lifestyle_image)){
                        $this->delete_file($request->old_lifestyle_image,PRODUCT_IMAGE_PATH);
                    }
                }
                if($request->hasFile('product_video_path')){
                    $product_video_path = $this->upload_file($request->file('product_video_path'),PRODUCT_VIDEO_PATH);
                    if(!empty($request->old_product_video_path)){
                        $this->delete_file($request->old_product_video_path,PRODUCT_VIDEO_PATH);
                    }
                }
                if($request->hasFile('product_brochure')){
                    $product_brochure = $this->upload_file($request->file('product_brochure'),PRODUCT_BROCHURE);
                    if(!empty($request->old_product_brochure)){
                        $this->delete_file($request->old_product_brochure,PRODUCT_BROCHURE);
                    }
                }

                $collection = $collection->merge(compact('image','product_video_path','lifestyle_image','product_brochure'));
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }

    public function show(Request $request)
    {
        if($request->ajax()){
            if (permission('product-view')) {
                $product = $this->model->with('brand','category','unit','purchase_unit','sale_unit','tax')->findOrFail($request->id);

                return view('product::details',compact('product'))->render();
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('product-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('product-delete')){
                $product = $this->model->find($request->id);
                $image = $product->image;
                $lifestyle_image = $product->lifestyle_image;
                $product_video_path = $product->product_video_path;
                $product_brochure = $product->product_brochure;
                $result = $product->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,PRODUCT_IMAGE_PATH);
                    }
                }
                if($result){
                    if(!empty($lifestyle_image)){
                        $this->delete_file($lifestyle_image,PRODUCT_IMAGE_PATH);
                    }
                }
                if($result){
                    if(!empty($product_video_path)){
                        $this->delete_file($product_video_path,PRODUCT_VIDEO_PATH);
                    }
                }
                if($result){
                    if(!empty($product_brochure)){
                        $this->delete_file($product_brochure,PRODUCT_BROCHURE);
                    }
                }
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('product-bulk-delete')){
                $products = $this->model->toBase()->select('image','lifestyle_image','product_video_path','product_brochure')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($products)){
                        foreach ($products as $product) {
                            if($product->image){
                                $this->delete_file($product->image,PRODUCT_IMAGE_PATH);
                            }
                            if($product->lifestyle_image){
                                $this->delete_file($product->lifestyle_image,PRODUCT_IMAGE_PATH);
                            }
                            if($product->product_video_path){
                                $this->delete_file($product->product_video_path,PRODUCT_VIDEO_PATH);
                            }
                            if($product->product_brochure){
                                $this->delete_file($product->product_brochure,PRODUCT_BROCHURE);
                            }
                        }
                    }
                }
                $output = $this->bulk_delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('product-edit')) {
                $result = $this->model->find($request->id)->update(['status'=>$request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function generate_code()
    {
        return Keygen::numeric(8)->generate();
    }

    public function populate_unit($id)
    {
        $units = Unit::where('base_unit',$id)->orWhere('id',$id)->pluck('unit_name','id');
        return json_encode($units);
    }

    public function product_autocomplete_search(Request $request)
    {
        if($request->ajax())
        {
            if(!empty($request->search))
            {
                $output = [];
                if(!$request->has('warehouse_id')){
                    $data = $this->model->where('name','like','%'.$request->search.'%')
                                    ->orWhere('code','like','%'.$request->search.'%')
                                    ->get();
                    if(!$data->isEmpty())
                    {
                        foreach ($data as $key => $value) {
                            $item['id'] = $value->id;
                            $item['value'] = $value->code.' - '.$value->name;
                            $item['label'] = $value->code.' - '.$value->name;
                            $output[] = $item;
                        }
                    }else{
                        $output['value'] = '';
                        $output['label'] = 'No Record Found';
                    }
                }else{
                    $search_text = $request->search;
                    $data = WarehouseProduct::with('product')->where([
                       [ 'warehouse_id', $request->warehouse_id],['qty','>',0]
                    ])->whereHas('product',function($q) use ($search_text){
                        $q->where('name','like','%'.$search_text.'%')
                        ->orWhere('code','like','%'.$search_text.'%');
                    })->get();

                    if(!$data->isEmpty())
                    {
                        foreach ($data as $key => $value) {
                            $item['id'] = $value->product->id;
                            $item['value'] = $value->product->code.' - '.$value->product->name;
                            $item['label'] = $value->product->code.' - '.$value->product->name;
                            $output[] = $item;
                        }
                    }else{
                        $output['value'] = '';
                        $output['label'] = 'No Record Found';
                    }
                }

                return $output;
            }
        }
    }

    public function product_search(Request $request)
    {
        if($request->ajax())
        {
            $code = explode('-',$request['data']);
            $product_data = $this->model->with('tax')->where('code',$code[0])->first();
            if($product_data)
            {
                $product['id']         = $product_data->id;
                $product['name']       = $product_data->name;
                $product['code']       = $product_data->code;
                if($request->type == 'purchase'){
                    $product['cost']       = $product_data->cost;
                }else{
                    $product['price']      = $product_data->price;
                }

                $product['tax_rate']   = $product_data->tax->rate;
                $product['tax_name']   = $product_data->tax->name;
                $product['tax_method'] = $product_data->tax_method;
                if($request->type == 'sale'){
                    $warehouse_product = WarehouseProduct::where([
                        'warehouse_id'=>$request->warehouse_id,'product_id'=>$product_data->id])->first();
                    $product['qty'] = $warehouse_product ? $warehouse_product->qty : 0;
                }


                $units = Unit::where('base_unit',$product_data->unit_id)->orWhere('id',$product_data->unit_id)->get();
                $unit_name            = [];
                $unit_operator        = [];
                $unit_operation_value = [];
                if($units)
                {
                    foreach ($units as $unit) {
                        if($request->type == 'purchase'){
                            if($product_data->purchase_unit_id == $unit->id)
                            {
                                array_unshift($unit_name,$unit->unit_name);
                                array_unshift($unit_operator,$unit->operator);
                                array_unshift($unit_operation_value,$unit->operation_value);
                            }else{
                                $unit_name           [] = $unit->unit_name;
                                $unit_operator       [] = $unit->operator;
                                $unit_operation_value[] = $unit->operation_value;
                            }
                        }else{
                            if($product_data->sale_unit_id == $unit->id)
                            {
                                array_unshift($unit_name,$unit->unit_name);
                                array_unshift($unit_operator,$unit->operator);
                                array_unshift($unit_operation_value,$unit->operation_value);
                            }else{
                                $unit_name           [] = $unit->unit_name;
                                $unit_operator       [] = $unit->operator;
                                $unit_operation_value[] = $unit->operation_value;
                            }
                        }

                    }
                }
                $product['unit_name'] = implode(',',$unit_name).',';
                $product['unit_operator'] = implode(',',$unit_operator).',';
                $product['unit_operation_value'] = implode(',',$unit_operation_value).',';
                return $product;
            }
        }
    }

    /* public function cs_list($id){
        if($request->ajax()){
            dd($id);
            if(permission('product-access')){
                $data = SubCategory::where('category_id','=',$id)->get();
                $output = $this->data_message($data);
                dd($output);
            }
            dd($output);
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    } */

    public function cs_list($id)
    {
        $data = SubCategory::where('category_id','=',$id)->pluck('name','id');
        return json_encode($data);
    }
    public function vo_list($id)
    {
        try {
            $inventory=[];
            $inventory_offer=[];
            //first check offer price
            $inventory_offer = Inventory::where('id',$id)
                    ->whereDate('offer_start', '<=', date('Y-m-d'))
                    ->whereDate('offer_end', '>=', date('Y-m-d'))
                    ->get();
            if($inventory_offer->isEmpty()){
                $inventory = Inventory::where('id',$id) //then check regular price
                    ->get();
            }

            $data = VariantOption::where('variant_id','=',$id)->pluck('name','id');
            $compactData = compact('data', 'inventory','inventory_offer');
            return json_encode($compactData);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>$e->getMessage()]);
        }
    }
}

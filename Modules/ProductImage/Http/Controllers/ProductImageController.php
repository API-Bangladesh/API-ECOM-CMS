<?php

namespace Modules\ProductImage\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Product\Entities\Product;
use Modules\ProductImage\Entities\ProductImage;
use Modules\ProductImage\Http\Requests\ProductImageFormRequest;

class ProductImageController extends BaseController
{
    use UploadAble;
    public function __construct(ProductImage $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('pimage-access')){
            $this->setPageData('Product Image','Product Image','fas fa-box');
            $data = [
                'products' => Product::all(),
            ];
            return view('productimage::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('pimage-access')){
            if($request->ajax()){
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

                    if(permission('pimage-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('pimage-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' .'name' . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('pimage-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->product->name??'';
                    $row[] = table_image($value->image,PRODUCT_MULTI_IMAGE_PATH,$value->product->name??"");
                    $row[] = permission('pimage-edit') ? change_status($value->id,$value->status,$value->product->name??"") : STATUS_LABEL[$value->status];

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

    public function store_or_update_data(ProductImageFormRequest $request)
    {
        if($request->ajax()){
            if(permission('pimage-add') || permission('pimage-edit')){
                $collection = collect($request->validated())->except(['fileUpload','product_id']);
                $collection = $this->track_data($request->update_id,$collection);
                //$oldImage = $request->old_image;
                if($request->hasFile('fileUpload')){
                    foreach($request->file('fileUpload') as $singleimage){

                        $product_id=$request->product_id;
                        $image = $this->upload_file($singleimage,PRODUCT_MULTI_IMAGE_PATH);

                        $collection = $collection->merge(compact('product_id','image'));
                        $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                        $output = $this->store_message($result,$request->update_id);
                       // $allImage[] =$singleImage;
                    }

                    /* if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,PRODUCT_MULTI_IMAGE_PATH);
                    } */
                }else{
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
                }



            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }


    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('pimage-edit')){
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
            if(permission('pimage-delete')){
                $pimage = $this->model->find($request->id);
                $image = $pimage->image;
                $result = $pimage->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,PRODUCT_MULTI_IMAGE_PATH);
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
            if(permission('pimage-bulk-delete')){
                $pimages = $this->model->toBase()->select('image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($pimages)){
                        foreach ($pimages as $pimage) {
                            if($pimage->image){
                                $this->delete_file($pimage->image,PRODUCT_MULTI_IMAGE_PATH);
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
            if (permission('pimage-edit')) {
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
}

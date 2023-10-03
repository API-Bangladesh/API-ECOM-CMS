<?php

namespace Modules\ProductLifestyle\Http\Controllers;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Product\Entities\Product;
use Modules\ProductLifestyle\Entities\ProductLifestyle;
use Modules\ProductLifestyle\Http\Requests\ProductLifestyleFormRequest;

class ProductLifestyleController extends BaseController
{
    use UploadAble;
    public function __construct(ProductLifestyle $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('limage-access')){
            $this->setPageData('Product Lifestyle Image','Product Lifestyle Image','fas fa-box');
            $data = [
                'products' => Product::all(),
            ];
            return view('productlifestyle::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('limage-access')){
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

                    if(permission('limage-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('limage-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' ."name" . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('limage-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->product->name??"";
                    $row[] = table_image($value->lifestyle_image,PRODUCT_LIFESTYLE_MULTI_IMAGE_PATH,$value->product->name??"");
                    $row[] = permission('limage-edit') ? change_status($value->id,$value->status,"name") : STATUS_LABEL[$value->status];

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

    public function store_or_update_data(ProductLifestyleFormRequest $request)
    {
        if($request->ajax()){
            if(permission('limage-add') || permission('limage-edit')){
                $collection = collect($request->validated())->except(['fileUpload','product_id']);
                $collection = $this->track_data($request->update_id,$collection);
                //$oldImage = $request->old_image;
                if($request->hasFile('fileUpload')){
                    foreach($request->file('fileUpload') as $singleimage){

                        $product_id=$request->product_id;
                        $lifestyle_image = $this->upload_file($singleimage,PRODUCT_LIFESTYLE_MULTI_IMAGE_PATH);

                        $collection = $collection->merge(compact('product_id','lifestyle_image'));
                        $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                        $output = $this->store_message($result,$request->update_id);
                       // $allImage[] =$singleImage;
                    }

                    /* if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,PRODUCT_MULTI_IMAGE_PATH);
                    } */
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
            if(permission('limage-edit')){
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
            if(permission('limage-delete')){
                $limage = $this->model->find($request->id);
                $lifestyle_image = $limage->lifestyle_image;
                $result = $limage->delete();
                if($result){
                    if(!empty($lifestyle_image)){
                        $this->delete_file($lifestyle_image,PRODUCT_LIFESTYLE_MULTI_IMAGE_PATH);
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
            if(permission('limage-bulk-delete')){
                $limages = $this->model->toBase()->select('lifestyle_image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($limages)){
                        foreach ($limages as $limage) {
                            if($limage->lifestyle_image){
                                $this->delete_file($limage->lifestyle_image,PRODUCT_LIFESTYLE_MULTI_IMAGE_PATH);
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
            if (permission('limage-edit')) {
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

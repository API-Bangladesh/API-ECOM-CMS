<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\UploadAble;
use Modules\Combo\Entities\Combo;
use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\InventoryImage;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Inventory\Http\Requests\InventoryImageFormRequest;

class InventoryImageController extends BaseController
{
   use UploadAble;
    public function __construct(InventoryImage $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('inventoryimage-access')){
            $this->setPageData('Inventory Image','Inventory Image','fas fa-box');
            $data = [
                'inventories' => Inventory::all(),
            ];
            return view('inventory::image-index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('inventoryimage-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                // if (!empty($request->product_id)) {
                //     $this->model->setProduct($request->product_id);
                // }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {

                    $no++;
                    $action = '';

                    if(permission('inventoryimage-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('inventoryimage-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->inventory->title . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('inventoryimage-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->inventory->title;
                    $row[] = table_image($value->image,INVENTORY_MULTI_IMAGE_PATH,$value->inventory->title);
                    $row[] = permission('inventoryimage-edit') ? change_status($value->id,$value->status,$value->inventory->title) : STATUS_LABEL[$value->status];



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

    public function store_or_update_data(InventoryImageFormRequest $request)
    {
        if($request->ajax()){
            if(permission('inventoryimage-add') || permission('inventoryimage-edit')){
                $collection = collect($request->validated())->except(['fileUpload']);
                $collection = $this->track_data($request->update_id,$collection);

                //$oldImage = $request->old_image;
                if($request->hasFile('fileUpload')){

                    foreach($request->file('fileUpload') as $singleimage){

                        $inventory_id=$request->inventory_id;
                        $image = $this->upload_file($singleimage,INVENTORY_MULTI_IMAGE_PATH);

                        $collection = $collection->merge(compact('inventory_id','image'));
                        $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                        $output = $this->store_message($result,$request->update_id);
                       // $allImage[] =$singleImage;
                    }

                    /* if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,INVENTORY_MULTI_IMAGE_PATH);
                    } */
                }else{

                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);

                }
            }
            else{
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
            if(permission('inventoryimage-edit')){
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
            if(permission('inventoryimage-delete')){
                $inventoryimage = $this->model->find($request->id);
                $image = $inventoryimage->image;
                $result = $inventoryimage->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,INVENTORY_MULTI_IMAGE_PATH);
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
            if(permission('inventoryimage-bulk-delete')){
                $inventoryimages = $this->model->toBase()->select('image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($inventoryimages)){
                        foreach ($inventoryimages as $inventoryimage) {
                            if($inventoryimage->image){
                                $this->delete_file($inventoryimage->image,INVENTORY_MULTI_IMAGE_PATH);
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
            if (permission('inventoryimage-edit')) {
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

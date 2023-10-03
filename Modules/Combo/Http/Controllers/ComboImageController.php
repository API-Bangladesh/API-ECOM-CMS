<?php

namespace Modules\Combo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\UploadAble;

use Modules\Base\Http\Controllers\BaseController;
use Modules\ComboCategory\Entities\ComboCategory;
use Modules\Combo\Entities\Combo;
use Modules\Combo\Entities\ComboItem;
use Modules\Combo\Entities\ComboImage;
use Modules\Inventory\Entities\Inventory;
use Modules\Combo\Http\Requests\ComboImageFormRequest;
use DB;

class ComboImageController extends BaseController
{
    use UploadAble;
    public function __construct(ComboImage $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('comboimage-access')){
            $this->setPageData('Combo Image','Combo Image','fas fa-box');
            $data = [
                'combos' => Combo::all(),
            ];
            return view('combo::image-index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('comboimage-access')){
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

                    if(permission('comboimage-edit')){
//                        $action .= ' <a class="dropdown-item edit_data" id="edit-modal" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('comboimage-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->combo->title . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('comboimage-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->combo->title;
                    $row[] = table_image($value->image,PRODUCT_MULTI_IMAGE_PATH,$value->combo->title);
                    $row[] = permission('comboimage-edit') ? change_status($value->id,$value->status,$value->combo->title) : STATUS_LABEL[$value->status];



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

    public function store_or_update_data(ComboImageFormRequest $request)
    {
        if($request->ajax()){
            if(permission('comboimage-add') || permission('comboimage-edit')){
                $collection = collect($request->validated())->except(['fileUpload']);
                $collection = $this->track_data($request->update_id,$collection);

                //$oldImage = $request->old_image;
                if($request->hasFile('fileUpload')){

                    foreach($request->file('fileUpload') as $singleimage){

                        $combo_id=$request->combo_id;
                        $image = $this->upload_file($singleimage,PRODUCT_MULTI_IMAGE_PATH);

                        $collection = $collection->merge(compact('combo_id','image'));
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
            if(permission('comboimage-edit')){
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
            if(permission('comboimage-delete')){
                $comboimage = $this->model->find($request->id);
                $image = $comboimage->image;
                $result = $comboimage->delete();
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
            if(permission('comboimage-bulk-delete')){
                $comboimages = $this->model->toBase()->select('image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($comboimages)){
                        foreach ($comboimages as $comboimage) {
                            if($comboimage->image){
                                $this->delete_file($comboimage->image,PRODUCT_MULTI_IMAGE_PATH);
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
            if (permission('comboimage-edit')) {
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

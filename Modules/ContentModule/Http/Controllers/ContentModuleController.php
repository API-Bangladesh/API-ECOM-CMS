<?php

namespace Modules\ContentModule\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ContentModule\Entities\ContentModule;
use Modules\ContentModule\Http\Requests\ContentModuleFormRequest;

class ContentModuleController extends BaseController
{
    use UploadAble;
    public function __construct(ContentModule $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('cmodule-access')){

            $this->setPageData('Content Module','Content Module','fas fa-th-list');
            return view('contentmodule::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('cmodule-access')){


            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('cmodule-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('cmodule-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if(permission('cmodule-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
		    $row[] = $value->id;
                    $row[] = $value->name;
                    $row[] = table_image($value->image,CONTENT_MODULE_IMAGE_PATH,$value->name);
                    $row[] = $value->module_description;
                    $row[] = $value->module_color;
                    $row[] = permission('cmodule-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];
                    //$row[] = permission('cmodule-edit') ? module_field_change_status($value->id,$value->item_title_status,$value->name) : MODULE_FIELD_STATUS_LABEL[$value->item_title_status];
                    //$row[] = permission('cmodule-edit') ? module_field_change_status($value->id,$value->item_sdesc,$value->name) : MODULE_FIELD_STATUS[$value->item_sdesc];
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

    public function store_or_update_data(ContentModuleFormRequest $request)
    {
        if($request->ajax()){
            if(permission('cmodule-add') || permission('cmodule-edit')){
                $collection = collect($request->validated())->except(['image']);
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),CONTENT_MODULE_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,CONTENT_MODULE_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image'));
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

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('cmodule-edit')){
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
            if(permission('cmodule-delete')){
                $result = $this->model->find($request->id)->delete();
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
            if(permission('cmodule-bulk-delete')){
                $result = $this->model->destroy($request->ids);
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
            if (permission('cmodule-edit')) {
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
    public function module_field_change_status(Request $request)
    {
        dd('test');
        if($request->ajax()){
            if (permission('cmodule-edit')) {
                $result = $this->model->find($request->id)
                ->update([
                    'title'=>$request->item_title_status
                ]);
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

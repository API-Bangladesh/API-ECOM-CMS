<?php

namespace Modules\ContentItem\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ContentCategory\Entities\ContentCategory;
use Modules\ContentItem\Entities\ContentItem;
use Modules\ContentItem\Http\Requests\ContentItemFormRequest;
use Modules\ContentModule\Entities\ContentModule;
use Modules\ContentType\Entities\ContentType;

class ContentItemController extends BaseController
{
    use UploadAble;
    public function __construct(ContentItem $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('citem-access')){
            $this->setPageData('Manage Contemt Item','Manage Contemt Item','fas fa-box');
            $data = [
                'content_categories' => ContentCategory::all(),
                'content_types' => ContentType::all(),
                'content_modules' => ContentModule::all(),
            ];
            return view('contentitem::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('citem-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->module_id)) {
                    $this->model->setModuleID($request->module_id);
                }
                if (!empty($request->type_id)) {
                    $this->model->setTypeID($request->type_id);
                }
                if (!empty($request->category_id)) {
                    $this->model->setCategoryID($request->category_id);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {

                    $no++;
                    $action = '';

                    if(permission('citem-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('citem-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('citem-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $value->id;
		            $row[] = $value->item_order;
                    $row[] = $value->name;
                    $row[] = table_image($value->image,CITEM_IMAGE_PATH,$value->name);
                    $row[] = table_image($value->item_image_banner,CITEM_BANNER_IMAGE_PATH,$value->name);
                    $row[] = $value->c_module->name;
                    $row[] = $value->c_category->name;
                    $row[] = $value->c_type->name;
                    $row[] = permission('citem-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];
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

    public function store_or_update_data(ContentItemFormRequest $request)
    {
//        return response()->json(['data'=>$request->all()]);
        if($request->ajax()){
            if(permission('citem-add') || permission('citem-edit')){
                $collection = collect($request->validated())->except(['image','item_image_banner']);
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                $item_image_banner = $request->old_item_image_banner;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),CITEM_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,CITEM_IMAGE_PATH);
                    }
                }
                if($request->hasFile('item_image_banner')){
                    $item_image_banner = $this->upload_file($request->file('item_image_banner'),CITEM_BANNER_IMAGE_PATH);

                    if(!empty($request->old_item_image_banner)){
                        $this->delete_file($request->old_item_image_banner,CITEM_BANNER_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image','item_image_banner'));
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
            if(permission('citem-edit')){
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
            if(permission('citem-delete')){
                $citem = $this->model->find($request->id);
                $image = $citem->image;
                $item_image_banner = $citem->item_image_banner;
                $result = $citem->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,CITEM_IMAGE_PATH);
                    }
                }
                if($result){
                    if(!empty($item_image_banner)){
                        $this->delete_file($item_image_banner,CITEM_BANNER_IMAGE_PATH);
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
            if(permission('citem-bulk-delete')){
                $citems = $this->model->toBase()->select('image','item_image_banner')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($citems)){
                        foreach ($citems as $citem) {
                            if($citem->image){
                                $this->delete_file($citem->image,CITEM_IMAGE_PATH);
                            }
			    if($citem->item_image_banner){
                                $this->delete_file($citem->item_image_banner,CITEM_BANNER_IMAGE_PATH);
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
            if (permission('citem-edit')) {
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

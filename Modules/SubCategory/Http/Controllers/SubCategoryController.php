<?php

namespace Modules\SubCategory\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Category\Entities\Category;
use Modules\SubCategory\Entities\SubCategory;
use Modules\SubCategory\Http\Requests\SubCategoryFormRequest;

class SubCategoryController extends BaseController
{
    use UploadAble;
    public function __construct(SubCategory $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('scategory-access')){
            $this->setPageData('Sub Category','Sub Category','fas fa-box');
            $data = [
                'Categories' => Category::all(),
            ];
            return view('subcategory::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('scategory-access')){
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

                    if(permission('scategory-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('scategory-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('scategory-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->category->name??'';
                    $row[] = $value->name??'';
                    $row[] = table_image($value->image,SUB_CATEGORY_IMAGE_PATH,$value->name);
                    $row[] = $value->sub_category_description;
                    $row[] = permission('scategory-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];



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

    public function store_or_update_data(SubCategoryFormRequest $request)
    {
        if($request->ajax()){
            if(permission('scategory-add') || permission('scategory-edit')){
                $collection = collect($request->validated())->except(['image']);
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),SUB_CATEGORY_IMAGE_PATH);
                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,SUB_CATEGORY_IMAGE_PATH);
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
            if(permission('scategory-edit')){
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
            if(permission('scategory-delete')){
                $scategory = $this->model->find($request->id);
                $image = $scategory->image;
                $result = $scategory->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,SUB_CATEGORY_IMAGE_PATH);
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
            if(permission('scategory-bulk-delete')){
                $scategorys = $this->model->toBase()->select('image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($scategorys)){
                        foreach ($scategorys as $scategory) {
                            if($scategory->image){
                                $this->delete_file($scategory->image,SUB_CATEGORY_IMAGE_PATH);
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
            if (permission('scategory-edit')) {
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

<?php

namespace Modules\Picture\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\GalleryCategory\Entities\GalleryCategory;
use Modules\Picture\Entities\Picture;
use Modules\Picture\Http\Requests\PictureFormRequest;

class PictureController extends BaseController
{
    use UploadAble;
    public function __construct(Picture $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('picture-access')){
            $this->setPageData('Picture','Picture','fas fa-box');
            $data = [
                'gallery_categories' => GalleryCategory::where('status','=',1)->get(),
            ];
            return view('picture::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('picture-access')){
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

                    if(permission('picture-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('picture-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('picture-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->name;
                    $row[] = $value->galleryCategory->name;
                    $row[] = table_image($value->image,PICTURE_IMAGE_PATH,$value->name);
                    $row[] = permission('picture-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];



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

    public function store_or_update_data(PictureFormRequest $request)
    {
        if($request->ajax()){
            if(permission('picture-add') || permission('picture-edit')){
                $collection = collect($request->validated())->except(['image']);
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),PICTURE_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,PICTURE_IMAGE_PATH);
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
            if(permission('picture-edit')){
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
            if(permission('picture-delete')){
                $picture = $this->model->find($request->id);
                $image = $picture->image;
                $result = $picture->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,PICTURE_IMAGE_PATH);
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
            if(permission('picture-bulk-delete')){
                $pictures = $this->model->toBase()->select('image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($pictures)){
                        foreach ($pictures as $picture) {
                            if($picture->image){
                                $this->delete_file($picture->image,PICTURE_IMAGE_PATH);
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
            if (permission('picture-edit')) {
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

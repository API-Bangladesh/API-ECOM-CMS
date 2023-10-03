<?php

namespace Modules\Video\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\GalleryCategory\Entities\GalleryCategory;
use Modules\Video\Entities\Video;
use Modules\Video\Http\Requests\VideoFormRequest;

class VideoController extends BaseController
{
    use UploadAble;
    public function __construct(Video $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('video-access')){
            $this->setPageData('Video','Video','fas fa-box');
            $data = [
                'gallery_categories' => GalleryCategory::where('status','=',1)->get(),
            ];
            return view('video::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('video-access')){
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

                    if(permission('video-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('video-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('video-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->name;
                    $row[] = $value->galleryCategory->name;
                    $row[] = $value->video_link;
                    $row[] = permission('video-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];



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

    public function store_or_update_data(VideoFormRequest $request)
    {
        if($request->ajax()){
            if(permission('video-add') || permission('video-edit')){
                $collection = collect($request->validated())->except(['video']);
                $collection = $this->track_data($request->update_id,$collection);
                $video = $request->old_video;
                if($request->hasFile('video')){
                    $video = $this->upload_file($request->file('video'),VIDEO_PATH);

                    if(!empty($request->old_video)){
                        $this->delete_file($request->old_video,VIDEO_PATH);
                    }
                }
                $collection = $collection->merge(compact('video'));
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
            if(permission('video-edit')){
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
            if(permission('video-delete')){
                $video = $this->model->find($request->id);
                $video = $video->video;
                $result = $video->delete();
                if($result){
                    if(!empty($video)){
                        $this->delete_file($video,VIDEO_PATH);
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
            if(permission('video-bulk-delete')){
                $videos = $this->model->toBase()->select('video')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($videos)){
                        foreach ($videos as $video) {
                            if($video->video){
                                $this->delete_file($video->video,VIDEO_PATH);
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
            if (permission('video-edit')) {
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

<?php

namespace Modules\Blog\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Http\Requests\BlogFormRequest;
use Modules\BlogCategory\Entities\BlogCategory;

class BlogController extends BaseController
{
    use UploadAble;
    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('blog-access')){
            $this->setPageData('Manage Blog','Manage Blog','fas fa-box');
            $data = [
                'blogCategories' => BlogCategory::all(),
            ];
            return view('blog::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('blog-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->category_id)) {
                    dd($request->category_id);
                    $this->model->setCategory($request->category_id);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('blog-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('blog-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('blog-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('blog-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $value->id;
                    $row[] = $value->blog_order;
                    $row[] = table_image($value->image,BLOG_IMAGE_PATH,$value->name);
                    $row[] = table_image($value->blog_banner_image,BLOG_IMAGE_PATH,$value->name);
                    $row[] = $value->name;
                    $row[] = $value->blogCategory->name;
                    $row[] = $value->blog_author;
                    $row[] = $value->blog_date;
                    $row[] = permission('blog-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];
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

    public function store_or_update_data(BlogFormRequest $request)
    {
        if($request->ajax()){
            if(permission('blog-add') || permission('blog-edit')){
                $collection = collect($request->validated())->except(['image','blog_banner_image']);
                $blog_video_path = $request->blog_video_path;
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                $blog_banner_image = $request->old_blog_banner_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),BLOG_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,BLOG_IMAGE_PATH);
                    }
                }
                if($request->hasFile('blog_banner_image')){
                    $blog_banner_image = $this->upload_file($request->file('blog_banner_image'),BLOG_IMAGE_PATH);

                    if(!empty($request->old_blog_banner_image)){
                        $this->delete_file($request->old_blog_banner_image,BLOG_IMAGE_PATH);
                    }
                }


                $collection = $collection->merge(compact('image','blog_banner_image'));
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
            if (permission('blog-view')) {
                $blog = $this->model->with('brand','category','unit','purchase_unit','sale_unit','tax')->findOrFail($request->id);

                return view('blog::details',compact('blog'))->render();
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('blog-edit')){
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
            if(permission('blog-delete')){
                $blog = $this->model->find($request->id);
                $image = $blog->image;
                $blog_banner_image = $blog->blog_banner_image;
                $result = $blog->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,BLOG_IMAGE_PATH);
                    }
                }
                if($result){
                    if(!empty($blog_banner_image)){
                        $this->delete_file($blog_banner_image,BLOG_IMAGE_PATH);
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
            if(permission('blog-bulk-delete')){
                $blogs = $this->model->toBase()->select('image','blog_banner_image')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($blogs)){
                        foreach ($blogs as $blog) {
                            if($blog->image){
                                $this->delete_file($blog->image,BLOG_IMAGE_PATH);
                            }
                            if($blog->blog_banner_image){
                                $this->delete_file($blog->blog_banner_image,BLOG_IMAGE_PATH);
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
            if (permission('blog-edit')) {
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

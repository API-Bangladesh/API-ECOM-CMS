<?php

namespace Modules\DocumentModule\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\DocumentCategory\Entities\DocumentCategory;
use Modules\DocumentModule\Entities\DocumentModule;
use Modules\DocumentModule\Http\Requests\DocumentFormRequest;

class DocumentModuleController extends BaseController
{
    use UploadAble;
    public function __construct(DocumentModule $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('document-access')){
            $this->setPageData('Item','Item','fas fa-box');
            $data = [
                'document_categories' => DocumentCategory::all(),
            ];
            return view('documentmodule::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('document-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->rcat_id)) {
                    $this->model->setRcatID($request->rcat_id);
                }
                if (!empty($request->special)) {
                    $this->model->setSpecial($request->special);
                }
                if (!empty($request->offer)) {
                    $this->model->setOffer($request->offer);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {


                    $no++;
                    $action = '';

                    if(permission('document-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('document-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('document-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    if($value->document)
                    {
                        $action .= '<a class="dropdown-item" href="'.asset('storage/'.PURCHASE_DOCUMENT_PATH.$value->document).'" download><i class="fas fa-download mr-2"></i> Document</a>';
                    }

                    $row = [];

                    if(permission('document-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $value->id;
                    $row[] = $value->document_order;
                    $row[] = table_image($value->image,DOCUMENT_IMAGE_PATH,$value->name);
                    $row[] = $value->name;
                    $row[] = $value->DocumentCategory->name;
                    $row[] = '<a href="'.asset('storage/'.DOCUMENT_IMAGE_PATH.$value->document_file).'" download><i class="fas fa-download mr-2"></i> Document</a>';
                    $row[] = permission('document-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];
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

    public function store_or_update_data(DocumentFormRequest $request)
    {
        if($request->ajax()){
            if(permission('document-add') || permission('document-edit')){
                $collection = collect($request->validated())->except(['image','document_file']);
                $collection = $this->track_data($request->update_id,$collection);
                $document_file = $request->old_document_file;
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),DOCUMENT_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,DOCUMENT_IMAGE_PATH);
                    }
                }
                if($request->hasFile('document_file')){
                    $document_file = $this->upload_file($request->file('document_file'),DOCUMENT_IMAGE_PATH);
                    if(!empty($request->old_document_file)){
                        $this->delete_file($request->old_document_file,DOCUMENT_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image','document_file'));
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
            if(permission('document-edit')){
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
            if(permission('document-delete')){
                $document = $this->model->find($request->id);
                $image = $document->image;
		$document_file= $document->document_file;
                $result = $document->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image,DOCUMENT_IMAGE_PATH);
                    }
 		   if(!empty($document_file)){
                        $this->delete_file($document_file,DOCUMENT_IMAGE_PATH);
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
            if(permission('document-bulk-delete')){
                $documents = $this->model->toBase()->select('image','document_file')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($documents)){
                        foreach ($documents as $document) {
                            if($document->image){
                                $this->delete_file($document->image,DOCUMENT_IMAGE_PATH);
                            }
			    if($document->document_file){
                                $this->delete_file($document->document_file,DOCUMENT_IMAGE_PATH);
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
            if (permission('document-edit')) {
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

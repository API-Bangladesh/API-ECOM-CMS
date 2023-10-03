<?php

namespace Modules\ComboCategory\Http\Controllers;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ComboCategory\Entities\ComboCategory;
use Modules\ComboCategory\Http\Requests\ComboCategoryFormRequest;

class ComboCategoryController extends BaseController
{
    use UploadAble;
    public function __construct(ComboCategory $model)
    {
        $this->model = $model;
    }

    public function index(){
//    dd($this->model->getDatatableList());
        if(permission('combocategory-access')){

            $this->setPageData('Combo Category','Combo Category','fas fa-th-list');
            return view('combocategory::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('combocategory-access')){


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

                    if(permission('combocategory-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('combocategory-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if(permission('combocategory-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->name;
                    $row[] = table_image($value->image,ComboCategory_IMAGE_PATH,$value->name);
                    $row[] = permission('combocategory-edit') ? change_status($value->id,$value->status,$value->name) : STATUS_LABEL[$value->status];;
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

    public function store_or_update_data(ComboCategoryFormRequest $request)
    {
        if($request->ajax()){
            if(permission('combocategory-add') || permission('combocategory-edit')){
                $collection = collect($request->validated())->except(['image']);
                $collection = $this->track_data($request->update_id,$collection);
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'),ComboCategory_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image,ComboCategory_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image'));
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());

                //for quantity
                $quantities = $request->input('quantity', []);

                // Loop through the 'quantity' array and use updateOrCreate for each value
                foreach ($quantities as $key => $quantity) {
                    $data = $collection->merge(['quantity' => $quantity]);
                    $this->model->updateOrCreate(['id' => $request->update_id], $data->all());
                }

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
            if(permission('combocategory-edit')){
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
            if(permission('combocategory-delete')){
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
            if(permission('combocategory-bulk-delete')){
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
            if (permission('combocategory-edit')) {
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

<?php

namespace Modules\Review\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Review\Entities\Review;

class ReviewController extends BaseController
{
    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('review-access')){
            $this->setPageData('reviews','reviews','fas fa-th-list');
            return view('review::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('review-access')){
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


                    $row = [];

                    $row[] = $value->id;
                    $row[] = REVIEW_LABEL[$value->ratting_number];
                    $row[] = $value->comments;
                    $row[] = $value->customers->name;
                    if($value->inventories->title??0){
                        $row[] = $value->inventories->title??'';
                    }else{
                        $row[] = $value->combos->title??'';
                    }
                    $row[] = permission('review-edit') ? change_status($value->id,$value->status,'') : STATUS_LABEL[$value->status];
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

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('review-edit')) {
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

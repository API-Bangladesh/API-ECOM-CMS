<?php

namespace Modules\Page\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Page\Entities\Page;
use Modules\Page\Http\Requests\PageRequest;

class PageController extends BaseController
{
    use UploadAble;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function index()
    {
//    dd($this->model->getDatatableList());
        if (permission('page-access')) {

            $this->setPageData('Page', 'Page', 'fas fa-th-list');
            return view('page::index');
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('page-access')) {


            if ($request->ajax()) {
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

                    if (permission('page-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('page-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->page . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if (permission('page-bulk-delete')) {
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->page;
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(),
                    $this->model->count_filtered(), $data);
            } else {
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(PageRequest $request)
    {
        if ($request->ajax()) {
            if (permission('page-add') || permission('page-edit')) {
                $collection = collect($request->validated());
                $collection = $this->track_data_except_created_by($request->update_id, $collection);

                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                $output = $this->store_message($result, $request->update_id);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('page-edit')) {
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('page-delete')) {
                $result = $this->model->find($request->id)->delete();
                $output = $this->delete_message($result);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('page-bulk-delete')) {
                $result = $this->model->destroy($request->ids);
                $output = $this->bulk_delete_message($result);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            if (permission('page-edit')) {
                $result = $this->model->find($request->id)->update(['status' => $request->status]);
                $output = $result ? ['status' => 'success', 'message' => 'Status has been changed successfully']
                    : ['status' => 'error', 'message' => 'Failed to change status'];
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

}



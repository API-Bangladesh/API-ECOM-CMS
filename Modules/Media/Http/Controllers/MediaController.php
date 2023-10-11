<?php

namespace Modules\Media\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Media\Entities\Media;
use Modules\Media\Http\Requests\MediaRequest;

class MediaController extends BaseController
{
    use UploadAble;

    public function __construct(Media $model)
    {
        $this->model = $model;
    }

    public function index()
    {
//    dd($this->model->getDatatableList());
        if (permission('media-access')) {

            $this->setPageData('Media', 'Media', 'fas fa-th-list');
            return view('media::index');
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('media-access')) {


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

                    if (permission('media-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('media-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if (permission('media-bulk-delete')) {
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->name;
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

    public function store_or_update_data(MediaRequest $request)
    {
        if ($request->ajax()) {
            if (permission('media-add') || permission('media-edit')) {
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
            if (permission('media-edit')) {
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
            if (permission('media-delete')) {
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
            if (permission('media-bulk-delete')) {
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
            if (permission('media-edit')) {
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




<?php

namespace Modules\B2B\Http\Controllers;

use App\Mail\OrderStatusChanged;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\B2B\Entities\B2B;
use Modules\Base\Http\Controllers\BaseController;
use Modules\B2B\Http\Requests\OrderFormRequest;
use Illuminate\Support\Facades\Mail;

class B2BController extends BaseController
{
    use UploadAble;

    public function __construct(B2B $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('b2b-access')) {
            $this->setPageData('B2B', 'B2B', 'fas fa-box');
            return view('b2b::index');
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('b2b-access')) {
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

                    if (permission('b2b-edit')) {
//                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('b2b-delete')) {
//                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->id . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-primary"></i> View</a>';
                    $row = [];

                    if (permission('b2b-bulk-delete')) {
//                        $row[] = table_checkbox($value->id);
                    }
                    $options = '<select name="status" id="status_id" class="form-control status_id" onchange="getStatus(this.value, ' . $value->id . ')">
                        <option value="">Select Please</option>
                        <option ' . (B2B::STATUS_PENDING == 1 ? 'selected' : '') . ' value="1">PENDING</option>
                        <option ' . (B2B::STATUS_IN_PROGRESS == 2 ? 'selected' : '') . ' value="2">IN PROGRESS</option>
                        <option ' . (B2B::STATUS_PROCESSING == 3 ? 'selected' : '') . ' value="3">PROCESSING</option>
                        <option ' . (B2B::STATUS_COMMUNICATED == 4 ? 'selected' : '') . ' value="4">COMMUNICATED</option>
                        <option ' . (B2B::STATUS_CANCELLED == 5 ? 'selected' : '') . ' value="5">CANCELLED</option>
                    </select>';

                    $row[] = $no;
                    $row[] = $value->name;
                    $row[] = $value->country_name;
                    $row[] = $value->product_code;
                    $row[] = $options;
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

    public function store_or_update_data(OrderFormRequest $request)
    {
        if ($request->ajax()) {
            if (permission('b2b-add') || permission('b2b-edit')) {
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id, $collection);

                //order quantity update product_id
                if (isset($request->product_id)) {
                    for ($i = 0; $i < count($request->product_id); $i++) {
                        if (isset($request->update_id, $request->type[$i], $request->product_id[$i], $request->quantity[$i]) && $request->type[$i] == 'product') {
                            OrderItem::where('order_id', $request->update_id)->where('inventory_id', $request->product_id[$i])->update(['quantity' => $request->quantity[$i]]);
                        } else if (isset($request->update_id, $request->type[$i], $request->product_id[$i], $request->quantity[$i]) && $request->type[$i] == 'combo') {
                            OrderItem::where('order_id', $request->update_id)->where('combo_id', $request->product_id[$i])->update(['quantity' => $request->quantity[$i]]);
                        }
                    }
                }
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


    public function view(Request $request)
    {
        if ($request->ajax()) {
            if (permission('b2b-edit')) {
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

    public function update_status(Request $request)
    {
        if ($request->ajax()) {
            if (permission('b2b-edit')) {
                $result = $this->model->find($request->id)->update(['status' => $request->status_id]);
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



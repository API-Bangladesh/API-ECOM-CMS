<?php

namespace Modules\CustomOrder\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomOrder\Entities\OrderMessage;
use Modules\Base\Http\Controllers\BaseController;
use Modules\CustomOrder\Http\Requests\OrderMessageRequest;

class CustomOrderMessageController extends BaseController
{
    use UploadAble;

    public function __construct(OrderMessage $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        // dd($this->model->getDatatableList());
        if (permission('ordermessage-access')) {
            $this->setPageData('Order Message', 'Order Message', 'fas fa-box');
//            $data = [
//                'categories' => Category::get()
//            ];
//            return view('customorder::order-message-index', $data);
            return view('customorder::order-message-index');
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('ordermessage-access')) {
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

                    if (permission('ordermessage-add')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-plus-square text-primary"></i> Add Order</a>';
                    }if (permission('ordermessage-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('ordermessage-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->media . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];
                    if (permission('ordermessage-bulk-delete')) {
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;

                    $row[] = $value->order_text;
                    $row[] = $value->media;
                    $row[] = $value->date_time;
                    $row[] = $value->info;
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

    public function store_or_update_data(OrderMessageRequest $request)
    {
        if ($request->ajax()) {
//            return $request->all();
            if (permission('ordermessage-add') || permission('ordermessage-edit')) {
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id, $collection);

                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());

                $output = $this->store_message($result, $request->update_id);
                return response()->json($output);

            } else {
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            return response()->json($this->access_blocked());
        }
    }

    public static function getUniqueId(Inventory $model)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[random_int(0, strlen($characters) - 1)];
        }
        if ($model->where('sku', $id)->exists()) {
            return self::getUniqueId($model);
        }
        return $id;
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('ordermessage-edit')) {
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
            if (permission('ordermessage-delete')) {
                // Check if the inventory exists
                $inventory = $this->model->find($request->id);

                if (!$inventory) {
                    return response()->json(['status' => 'error', 'message' => 'Inventory not found']);
                }
                // Check if the product is associated with any inventory
                $inventoriesCount = $inventory->comboItems()->count();

                if ($inventoriesCount > 0) {
                    return response()->json(['status' => 'error', 'message' => 'Inventory is associated with combo item and cannot be deleted!']);
                }
                $inventory_varient = InventoryVariant::where('inventory_id', $request->id)->delete();

                $inventory->delete();
                $output = $this->delete_message($inventory);
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
            if (permission('ordermessage-bulk-delete')) {
                $inventory_varient = InventoryVariant::whereIn('inventory_id', $request->ids)->delete();
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
            if (permission('ordermessage-edit')) {
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


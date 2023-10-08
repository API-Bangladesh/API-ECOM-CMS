<?php

namespace Modules\CustomOrder\Http\Controllers;

use App\Models\Setting;
use App\Traits\UploadAble;
use http\Env\Response;
use Illuminate\Http\Request;
use Modules\Address\Entities\Address;
use Modules\Address\Entities\Upazila;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Customers\Entities\Customers;
use Modules\CustomOrder\Entities\CustomOrder;
use Modules\CustomOrder\Http\Requests\CustomerAddressRequest;
use Modules\CustomOrder\Http\Requests\CustomOrderRequest;
use Modules\Inventory\Entities\Inventory;
use Modules\Location\Entities\District;
use Modules\Location\Entities\Division;
use Modules\Order\Entities\OrderItem;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Product\Entities\Product;
use Modules\CustomOrder\Http\Requests\PageRequest;
use Modules\CustomOrder\Entities\PageModel;
use DB;
use Modules\Variant\Entities\Variant;
use setasign\Fpdi\PdfReader\Page;

class CustomOrderController extends BaseController
{
    use UploadAble;

    public function __construct(CustomOrder $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('customorder-access')) {
            $this->setPageData('SM Order', 'SM Order', 'fas fa-box');
            $data['divisions'] = Division::get();

            $data['payment_methods'] = PaymentMethod::get();
            $data['customers'] = Customers::get();
            $data['inventories'] = Inventory::get();
            $data['variants'] = Variant::get();
            return view('customorder::index',$data);
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('customorder-access')) {
            if ($request->ajax()) {
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->category_id)) {
                    $this->model->setCategory($request->category_id);
                }
                if (!empty($request->product_id)) {
                    $this->model->setProduct($request->product_id);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {

                    $no++;
                    $action = '';
                    if (permission('customorder-view')) {
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-primary"></i> View</a>';
                    }
                    if (permission('customorder-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('customorder-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->title . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];
                    if (permission('customorder-bulk-delete')) {
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;

                    $row[] = $value->customer->name??'';
                    $row[] = $value->customer->email??'';
                    $row[] = $value->customer->phone_number??'';

                    $order_options = '<select name="order_status_id" id="order_status_id" class="form-control order_status_id" onchange="getOrderStatus(this.value, '.$value->id.')">
                        <option value="">Select Please</option>
                        <option '. ($value->order_status_id == 6 ? 'selected' : '') .' value="6">NEW</option>
                        <option '. ($value->order_status_id == 7 ? 'selected' : '') .' value="7">CONFIRMED</option>
                        <option '. ($value->order_status_id == 1 ? 'selected' : '') .' value="1">PENDING</option>
                        <option '. ($value->order_status_id == 2 ? 'selected' : '') .' value="2">PROCESSING</option>
                        <option '. ($value->order_status_id == 3 ? 'selected' : '') .' value="3">SHIPPED</option>
                        <option '. ($value->order_status_id == 4 ? 'selected' : '') .' value="4">DELIVERED</option>
                        <option '. ($value->order_status_id == 5 ? 'selected' : '') .' value="5">CANCELED</option>
                        <option '. ($value->order_status_id == 8 ? 'selected' : '') .' value="8">RETURNED</option>
                    </select>';
                    $row[] = $order_options;
                    $row[] = permission('order-edit') ? change_payment_status($value->id,$value->payment_status_id??0,$value->payment_status_id??0) : PAYMENT_STATUS_LABEL[$value->payment_status_id??0];
//                    $row[] = permission('customorder-edit') ? change_status($value->id, $value->status, $value->customer->name) : STATUS_LABEL[$value->status];
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

    public function view(Request $request)
    {
        if ($request->ajax()) {
            if (permission('customorder-view')) {
                $data = $this->model->findOrFail($request->id);
                $data->load('orderItems','customer');
//                $data['inventories'] = Inventory::get();
                $data['logo'] = Setting::all();
                $output = $this->data_message($data);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function store_or_update_data(CustomOrderRequest $request)
    {
        if ($request->ajax()) {
            if (permission('customorder-add') || permission('customorder-edit')) {
                $collection = collect($request->validated());

                $collection = $this->track_data($request->update_id, $collection);

                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());

                if (isset($request->update_id) && $request->update_id !== '') {
                    OrderItem::where('order_id', $request->update_id)->delete();

                    for ($i = 0; $i < count($request->inventory_id); $i++) {
                        if (isset($request->inventory_id[$i]) && isset($request->inventory_id[$i])
                            && $request->inventory_id[$i] !== '' && $request->inventory_id[$i] !== '') {
                            OrderItem::create([
                                'order_id' => $result->id,
                                'type' => 'product',
                                'inventory_id' => $request->inventory_id[$i],
                                'quantity' => $request->quantity[$i],
                                'unit_price' => $request->unit_price[$i]
                            ]);
                        }
                    }
                } else {
                    for ($j = 0; $j < count($request->inventory_id); $j++) {
                        if (isset($request->inventory_id[$j]) && isset($request->inventory_id[$j])
                            && $request->inventory_id[$j] !== '' && $request->inventory_id[$j] !== '') {

                            OrderItem::create([
                                'order_id' => $result->id,
                                'type' => 'product',
                                'inventory_id' => $request->inventory_id[$j],
                                'quantity' => $request->quantity[$j],
                                'unit_price' => $request->unit_price[$j]
                            ]);
                        }
                    }
                }

                $output = $this->store_message($result, $request->update_id);
                return response()->json($output);

            }else {
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            return response()->json($this->access_blocked());
        }

    }

    public function customer_address(Request $request){
        if($request->type=='customer_id'){
            $address = Address::with('customer','division','district','upazila')->where('customer_id',$request->customer_id)->get();
        }else if($request->type=='address_id'){
            $address = Address::with('customer','division','district','upazila')->where('id',$request->address_id)->get();
        }

        return response()->json(['data'=>$address]);
    }

    public function change_payment_status(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-edit')) {
                $result = $this->model->find($request->id)->update(['payment_status_id' => $request->status]);
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
            if (permission('customorder-edit')) {
                $data = $this->model->findOrFail($request->id);
                $data->load('orderItems');
                $data['all_inventories'] = Inventory::get();
               // $data['variants'] = Variant::get();
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
            if (permission('customorder-delete')) {
                // Check if the inventory exists
                $order = $this->model->find($request->id);
                if (!$order) {
                    return response()->json(['status' => 'error', 'message' => 'Order not found']);
                }
                // Check if the product is associated with any inventory
                $orderItemCount = $order->orderItems()->count();
                if ($orderItemCount < 1) {
                    return response()->json(['status' => 'error', 'message' => 'Order item can not be deleted!']);
                }
                $order_item = OrderItem::where('order_id', $request->id)->delete();
                $order->delete();
                $output = $this->delete_message($order);
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
            if (permission('customorder-bulk-delete')) {
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

            if (permission('customorder-edit')) {
                $result = $this->model->find($request->id);
                if($result){
                    $result = $result->update(['order_status_id' => $request->order_status_id]);
                }
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

    public function district_by_division(Request $request)
    {
        $result = District::where('division_id',$request->div_id)->get();
        return response()->json($result);
    }
    public function upazila_by_district(Request $request)
    {
        $result = Upazila::where('district_id',$request->dis_id)->get();
        return response()->json($result);
    }

    public function save_customer(CustomerAddressRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            if (permission('customorder-add') || permission('customorder-edit')) {
                try{
                    $collection = collect($request->validated());

                    //customer data save
                    $data_customer = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'gender' => $request->gender,
                        'phone_number' => $request->phone_number,
                        'date_of_birth' => $request->date_of_birth,
                    ];

                    $customer = Customers::updateOrCreate(
                        ['id' => $request->update_customerid], // The unique column to identify the record
                        $data_customer
                    );

                    //address data save
                    $data_address = [
                        'title' => $request->title,
                        'name' => $request->customer_name,
                        'address_line_1' => $request->address_line_1,
                        'address_line_2' => $request->address_line_2,
                        'division_id' => $request->division_id,
                        'district_id' => $request->district_id,
                        'upazila_id' => $request->upazila_id,
                        'postcode' => $request->postcode,
                        'phone' => $request->phone,
                        'customer_id' => $customer['id']??0,
                    ];

                    $result = Address::updateOrCreate(
                        ['id' => $request->update_addressid], // The unique column to identify the record
                        $data_address
                    );

                    $output = $this->store_message($result, $request->update_addressid);
                    DB::commit();
                    return response()->json($output);

                }catch(\Exception $e){
                    DB::rollBack();
                    $output = $this->access_blocked();
                    return response()->json(['messsage'=>$e->getMessage()]);
                    return response()->json($output);
                }

            } else {
                DB::rollBack();
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            DB::rollBack();
            return response()->json($this->access_blocked());
        }
    }

    public function save_page(PageRequest $request){
        if ($request->ajax()) {
            DB::beginTransaction();
            if (permission('customorder-add') || permission('customorder-edit')) {
                try{
                    $collection = collect($request->validated());

                    //page data save
                    $data_page = [
                        'page' => $request->page,
                    ];

                    $result = PageModel::updateOrCreate(
                        ['id' => $request->update_id], // The unique column to identify the record
                        $data_page
                    );

                    $output = $this->store_message($result, $request->update_id);

                    DB::commit();
                    return response()->json($output);

                }catch(\Exception $e){
                    DB::rollBack();
                    $output = $this->access_blocked();
                    return response()->json(['messsage'=>$e->getMessage()]);
                    return response()->json($output);
                }

            } else {
                DB::rollBack();
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            DB::rollBack();
            return response()->json($this->access_blocked());
        }
    }
    public function get_pages(){
        return $data = PageModel::get();
    }
}



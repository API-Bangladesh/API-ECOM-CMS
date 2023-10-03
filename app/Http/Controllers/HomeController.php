<?php

namespace App\Http\Controllers;

use Modules\Customers\Entities\Customers;
use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Modules\Inventory\Entities\Inventory;
use Modules\Combo\Entities\Combo;

class HomeController extends Controller
{
    protected function setPageData($page_title,$sub_title,$page_icon)
    {
        view()->share(['page_title'=>$page_title,'sub_title'=>$sub_title,'page_icon'=>$page_icon]);
    }

    public function index()
    {
        if (permission('dashboard-access')) {
            $this->setPageData('Dashboard','Dashboard','fas fa-tachometer-alt');
            $customers = Customers::all()->count();
            $total_orders = Order::all()->count();
            $today_orders = Order::whereDate('created_at', Carbon::today())->get()->count();
            $pending_orders = Order::where('order_status_id','=',1)->get()->count();
            $process_orders = Order::where('order_status_id','=',2)->get()->count();
            $shipped_orders = Order::where('order_status_id','=',3)->get()->count();
            $delivered_orders = Order::where('order_status_id','=',4)->get()->count();
            $cancel_orders = Order::where('order_status_id','=',5)->get()->count();
            $inventory_qtys = Inventory::whereColumn('stock_quantity', '<=', 'reorder_quantity')->get();
            $combo_qtys = Combo::whereColumn('stock_quantity', '<=', 'reorder_quantity')->get();

            return view('home',compact('customers','total_orders','today_orders','pending_orders','process_orders','shipped_orders','delivered_orders','cancel_orders','inventory_qtys','combo_qtys'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function dashboard_data($start_date,$end_date)
    {
        if($start_date && $end_date)
        {
            $sale = Sale::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $purchase = Purchase::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $customer = Customer::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->get()->count();

            $supplier = Supplier::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->get()->count();

            $expense = Expense::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('amount');

            $data = [
                'sale' => number_format($sale,2,'.',','),
                'purchase' => number_format($purchase,2,'.',','),
                'profit' => number_format(($sale - $purchase),2,'.',','),
                'customer' => $customer,
                'supplier' => $supplier,
                'expense' => number_format($expense,2,'.',','),
            ];

            return response()->json($data);
        }
    }
    

    public function unauthorized()
    {
        $this->setPageData('Unathorized','Unathorized','fas fa-ban');
        return view('unauthorized');
    }
}

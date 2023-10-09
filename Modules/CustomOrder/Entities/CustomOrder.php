<?php

namespace Modules\CustomOrder\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Modules\Customers\Entities\Customers;
use Modules\Order\Entities\OrderItem;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Base\Entities\BaseModel;

class CustomOrder extends BaseModel
{
    use HasFactory;

    /**
     * Constants
     */

    const ORDER_STATUS_PENDING = 1;
    const ORDER_STATUS_PROCESSING = 2;
    const ORDER_STATUS_SHIPPED = 3;
    const ORDER_STATUS_DELIVERED = 4;
    const ORDER_STATUS_CANCELED = 5;
    const ORDER_STATUS_NEW =6;
    const ORDER_STATUS_CONFIRMED =7;
    const ORDER_STATUS_RETURNED =8;

    /*const ORDER_STATUS_RETURNED = 6;
    const ORDER_STATUS_REFUNDED = 7;*/

    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_STATUS_UNPAID = 2;

    protected $table = 'orders';

    protected $fillable = ['order_date', 'customer_id', 'billing_address', 'shipping_address',
        'total', 'discount', 'shipping_charge','special_note','media', 'tax', 'grand_total', 'payment_method_id',
        'payment_details', 'payment_status_id', 'order_status_id', 'created_at', 'updated_at'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if (permission('order-bulk-delete')) {
            $this->column_order = [null, 'id', 'order_date', 'shipping_address', 'total', null];
        } else {
            $this->column_order = ['id', 'order_date', 'shipping_address', 'total', null];
        }

        $query = self::with('orderItems','customer','orderMessage');
//        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/

        if (!empty($this->name)) {
//            $query->where('shipping_address', 'like', '%' . $this->name . '%');
            $nam = $this->name;
            //customer passes as $customerQuery
            $query->whereHas('customer', function ($customerQuery) use ($nam) {
                $customerQuery->where('name','like','%'. $nam.'%');
            });
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
    public function orderMessage()
    {
        return $this->belongsTo(OrderMessage::class, 'order_message_id', 'id')->with('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->with('inventory', 'combo');
//        return $this->hasMany(OrderItem::class, 'order_id', 'id')->with('inventory', 'combo');
    }

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderFactory::new();
    }
}



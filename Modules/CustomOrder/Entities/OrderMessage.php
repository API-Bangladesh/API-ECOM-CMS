<?php

namespace Modules\CustomOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\BaseModel;
use Modules\Order\Entities\Order;
use Modules\Media\Entities\Media;

class OrderMessage extends BaseModel
{
    use HasFactory;

    protected $table = 'order_messages';

    protected $fillable = ['id', 'page_id', 'order_text', 'media_id', 'date_time', 'info', 'created_at', 'updated_at'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {

        if (permission('ordermessage-bulk-delete')) {
            $this->column_order = [null, 'title', 'sale_price', 'stock_quantity', 'status', null];
        } else {
            $this->column_order = ['title', 'sale_price', 'stock_quantity', 'status', null];
        }

//        $query = self::toBase();
        $query = self::with('order','page','media');

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('order_text', 'like', '%' . $this->name . '%');
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
    public function order(){
        return $this->belongsTo(Order::class,'id','order_message_id');
    }
    public function page(){
        return $this->belongsTo(PageModel::class,'page_id','id');
    }
    public function media(){
        return $this->belongsTo(Media::class,'media_id','id');
    }
}


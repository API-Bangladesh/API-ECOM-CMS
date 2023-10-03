<?php

namespace Modules\ProductLifestyle\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\Product\Entities\Product;

class ProductLifestyle extends BaseModel
{
    protected $table = 'product_lifestyles';
    protected $fillable = ['name', 'product_id', 'lifestyle_image', 'status', 'created_by', 'updated_by'];

    public $timestamps = false;


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

     protected $name;
     protected $product_id;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setProduct($product_id)
    {
        $this->product_id = $product_id;
    }

    private function get_datatable_query()
    {
        if(permission('limage-bulk-delete')){
            $this->column_order = [null,'id','name', 'product_id', 'lifestyle_image', 'status',null];
        }else{
            $this->column_order = ['id','name', 'product_id', 'lifestyle_image', 'status',null];
        }

        $query = self::with('product');


        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->product_id)) {
            $query->where('product_id', $this->product_id);
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
}

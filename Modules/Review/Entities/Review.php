<?php

namespace Modules\Review\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\BaseModel;
use Modules\Combo\Entities\Combo;
use Modules\Inventory\Entities\Inventory;
use Modules\Customers\Entities\Customers;

class Review extends BaseModel
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = ['id','ratting_number','comments','customer_id','inventory_id','combo_id',
    'status','created_at','updated_at'];


    public function customers()
    {
        return $this->hasOne(Customers::class, 'id','customer_id');
    }

    public function inventories()
    {
        return $this->hasOne(Inventory::class, 'id','inventory_id');
    }
    public function combos()
    {
        return $this->hasOne(Combo::class, 'id','combo_id');
    }

    

    private function get_datatable_query()
    {
        if(permission('ccategory-bulk-delete')){
            $this->column_order = [null,'id','ratting_number','comments','customer_id','inventory_id','combo_id',
            'status',null];
        }else{
            $this->column_order = ['id','ratting_number','comments','customer_id','inventory_id','combo_id',
            'status',null];
        }

        
        $query = self::with('customers','inventories','combos');

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
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

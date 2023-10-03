<?php

namespace Modules\VariantOption\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Variant\Entities\Variant;

class VariantOption extends BaseModel
{
    protected $table = 'variant_options';
    protected $fillable = ['name', 'variant_id','status', 'created_by', 'updated_by'];


    public function variant()
    {
        return $this->belongsTo(Variant::class,'variant_id','id');
    }


     protected $name;
     protected $variant_id;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setCategoryID($variant_id)
    {
        $this->variant_id = $variant_id;
    }

    private function get_datatable_query()
    {
        if(permission('citem-bulk-delete')){
            $this->column_order = [null,'id','name', 'variant_id','status',null];
        }else{
            $this->column_order = ['id','name', 'variant_id','status',null];
        }

        $query = self::with('variant');


        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->variant_id)) {
            $query->where('variant_id', $this->variant_id);
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

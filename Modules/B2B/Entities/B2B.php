<?php

namespace Modules\B2B\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class B2B extends BaseModel
{
    use HasFactory;

    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_PROCESSING = 3;
    const STATUS_COMMUNICATED = 4;
    const STATUS_CANCELLED = 5;

    protected $table = 'b2b';

    protected $fillable = ['country_name','name','product_name','product_code','product_quantity',
        'contact_number','email_address','status'
    ];

    protected static function newFactory()
    {
        return \Modules\B2B\Database\factories\B2BFactory::new();
    }

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('b2b-bulk-delete')){
            $this->column_order = [null,'name','country_name','product_code','status',null];
        }else{
            $this->column_order = ['name','country_name','product_code','status',null];
        }

        $query = self::toBase();

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

<?php

namespace Modules\ContentModule\Entities;

use Modules\Base\Entities\BaseModel;

class ContentModule extends BaseModel
{
    protected $table = 'content_modules';

    protected $fillable = ['name','image','module_description','module_color','status','item_title_status','item_sdesc_status','created_by','updated_by'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('ccategory-bulk-delete')){
            $this->column_order = [null,'id','name','image','module_description','module_color','status','item_title_status','item_sdesc_status',null];
        }else{
            $this->column_order = ['id','name','image','module_description','module_color','status','item_title_status','item_sdesc_status',null];
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

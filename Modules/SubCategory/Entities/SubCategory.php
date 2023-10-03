<?php

namespace Modules\SubCategory\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\Category\Entities\Category;

class SubCategory extends BaseModel
{
    protected $table = 'sub_categories';
    protected $fillable = ['name', 'category_id', 'image', 'sub_category_description', 'status', 'created_by', 'updated_by'];


    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

     protected $name;
     protected $category_id;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setCategory($category_id)
    {
        $this->category_id = $category_id;
    }

    private function get_datatable_query()
    {
        if(permission('citem-bulk-delete')){
            $this->column_order = [null,'id','name', 'category_id', 'image', 'sub_category_description', 'status',null];
        }else{
            $this->column_order = ['id','name', 'category_id', 'image', 'sub_category_description', 'status',null];
        }

        $query = self::with('category');


        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->category_id)) {
            $query->where('category_id', $this->category_id);
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

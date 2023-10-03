<?php

namespace Modules\ContentItem\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\ContentCategory\Entities\ContentCategory;
use Modules\ContentModule\Entities\ContentModule;
use Modules\ContentType\Entities\ContentType;

class ContentItem extends BaseModel
{
    protected $table = 'content_items';
    protected $fillable = ['name', 'category_id', 'type_id', 'module_id', 'image', 'item_image_banner', 'item_link', 'item_video_link','item_order',
     'item_date', 'item_short_desc', 'item_long_desc', 'status', 'created_by', 'updated_by'];


    public function c_category()
    {
        return $this->belongsTo(ContentCategory::class,'category_id','id');
    }
    public function c_type()
    {
        return $this->belongsTo(ContentType::class, 'type_id','id');
    }
    public function c_module()
    {
        return $this->belongsTo(ContentModule::class, 'module_id','id');
    }

     protected $name;
     protected $module_id;
     protected $type_id;
     protected $category_id;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setModuleID($module_id)
    {
        $this->module_id = $module_id;
    }
    public function setTypeID($type_id)
    {
        $this->type_id = $type_id;
    }
    public function setCategoryID($category_id)
    {
        $this->category_id = $category_id;
    }

    private function get_datatable_query()
    {
        if(permission('citem-bulk-delete')){
            $this->column_order = [null,'id','name', 'image', 'item_image_banner', 'category_id', 'type_id', 'module_id', 'item_link', 'item_video_link','item_order',
            'item_date', 'item_short_desc', 'item_long_desc', 'status',null];
        }else{
            $this->column_order = ['id','name', 'image', 'item_image_banner', 'category_id', 'type_id', 'module_id', 'item_link', 'item_video_link','item_order',
            'item_date', 'item_short_desc', 'item_long_desc', 'status',null];
        }

        $query = self::with('c_category','c_type','c_module');


        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->module_id)) {
            $query->where('module_id', $this->module_id);
        }
        if (!empty($this->type_id)) {
            $query->where('type_id', $this->type_id);
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

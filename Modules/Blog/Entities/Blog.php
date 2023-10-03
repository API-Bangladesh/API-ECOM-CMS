<?php

namespace Modules\Blog\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\BlogCategory\Entities\BlogCategory;

class Blog extends BaseModel
{

    protected $fillable = ['name', 'blog_category_id','blog_author','image','blog_banner_image', 'blog_date', 'blog_short_desc','blog_long_desc','status','blog_order','created_by','updated_by'];


    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id','id');
    }

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }


    private function get_datatable_query()
    {
        if(permission('blog-bulk-delete')){
            $this->column_order = [null,'id','name', 'blog_category_id','blog_author','image','blog_banner_image', 'blog_date', 'blog_short_desc','blog_long_desc','status','blog_order',null];
        }else{
            $this->column_order = ['id','name', 'blog_category_id','blog_author','image','blog_banner_image', 'blog_date', 'blog_short_desc','blog_long_desc', 'status','blog_order',null];
        }

        $query = self::with('blogCategory');

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

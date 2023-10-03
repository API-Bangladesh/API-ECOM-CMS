<?php

namespace Modules\Video\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\GalleryCategory\Entities\GalleryCategory;

class Video extends BaseModel
{
    protected $table = 'videos';
    public $timestamps = false;
    protected $fillable = ['name','gallery_category_id','video_link','video', 'status', 'created_by', 'updated_by'];

    public function galleryCategory()
    {
        return $this->belongsTo(GalleryCategory::class,'gallery_category_id','id');
    }

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('video-bulk-delete')){
            $this->column_order = [null,'id','name','gallery_category_id','video_link','image','status',null];
        }else{
            $this->column_order = ['id','name','gallery_category_id','video_link','image','status',null];
        }

        $query = self::with('galleryCategory');

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

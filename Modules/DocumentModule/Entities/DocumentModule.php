<?php

namespace Modules\DocumentModule\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\DocumentCategory\Entities\DocumentCategory;

class DocumentModule extends BaseModel
{
    protected $table = 'documents';
    //use HasFactory;
    protected $fillable = ['name','document_category_id','document_count','image','document_file','document_desc','status','document_order', 'created_by', 'updated_by'];


    public function DocumentCategory()
    {
        return $this->belongsTo(DocumentCategory::class,'document_category_id','id');
    }



    protected $name;

    public function setName($name)
    {
        $this->_name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('document-bulk-delete')){
            $this->column_order = [null,'id','name','document_category_id','document_count','image','document_file','document_desc','status','document_order',null];
        }else{
            $this->column_order = ['id','name','document_category_id','document_count','image','document_file','document_desc','status','document_order',null];
        }

        $query = self::with('DocumentCategory');

        /*****************
         * *Search Data **
         ******************/

        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
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

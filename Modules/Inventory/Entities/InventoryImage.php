<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\Inventory;
use Modules\Base\Entities\BaseModel;

class InventoryImage extends BaseModel
{
    use HasFactory;

    protected $fillable = ['inventory_id', 'image', 'status', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
    private function get_datatable_query()
    {
        if(permission('pimage-bulk-delete')){
            $this->column_order = [null,'id', 'inventory_id', 'image', 'status',null];
        }else{
            $this->column_order = [null,'id', 'inventory_id', 'image', 'status',null];
        }

        $query = self::with('inventory');


        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->inventory_id)) {
            $query->where('inventory_id', $this->inventory_id);
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


    protected static function newFactory()
    {
        return \Modules\InventoryImage\Database\factories\InventoryImageFactory::new();
    }
}

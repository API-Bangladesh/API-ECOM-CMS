<?php

namespace Modules\Combo\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ComboCategory\Entities\ComboCategory;
use Modules\ComboImage\Entities\ComboImage;
use Modules\Base\Entities\BaseModel;

/**
 * @method static findOrFail($id)
 */
class Combo extends BaseModel
{
    use HasFactory;

    protected $table = 'combos';

    protected $fillable = ['id','combo_category_id','title','product_short_desc','product_long_desc','image',
    'lifestyle_image','sku','sale_price','offer_price',
    'offer_start','quantity','offer_end','stock_quantity','reorder_quantity','is_special_deal','is_manage_stock',
    'min_order_quantity','status','created_at','updated_at'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('ccategory-bulk-delete')){
            $this->column_order = [null,'id','name','image','description','status',null];
        }else{
            $this->column_order = ['id','name','image','description','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('title', 'like', '%' . $this->name . '%');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comboCategory()
    {
        return $this->belongsTo(ComboCategory::class, 'combo_category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comboItems()
    {
        return $this->hasMany(ComboItem::class, 'combo_id', 'id')->with('inventory','inventoryVariants');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comboImages()
    {
        return $this->hasMany(ComboImage::class, 'combo_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Combo\Database\factories\ComboFactory::new();
    }
    public function inventoryComboItems()
    {
        return $this->hasMany(ComboItem::class, 'combo_id', 'id');
    }
}

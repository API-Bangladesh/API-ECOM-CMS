<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Entities\BaseModel;
use Modules\Combo\Entities\ComboItem;
use Modules\Product\Entities\Product;


class Inventory extends BaseModel
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = ['product_id', 'title', 'sku', 'sale_price', 'offer_price',
        'offer_start', 'offer_end', 'stock_quantity', 'reorder_quantity', 'is_special_deal',
        'is_manage_stock', 'min_order_quantity', 'image', 'status', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id', 'id');
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
        logger('Hello');

        if (permission('ccategory-bulk-delete')) {
            $this->column_order = [null, 'title', 'sale_price', 'stock_quantity', 'status', null];
        } else {
            $this->column_order = ['title', 'sale_price', 'stock_quantity', 'status', null];
        }
//        logger($this->category_id);
      $query = self::toBase();
//        $query = self::with('product');
//        $query->when($this->category_id, function ($query) {
//            $query->whereHas('product', function ($query) {
//                $query->where('category_id', $this->category_id);
//            });
//        });


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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function combos()
    {
        return $this->hasMany(Combo::class, 'combo_category_id', 'id');
    }
    public function comboItems()
    {
        return $this->hasMany(ComboItem::class, 'inventory_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryImages()
    {
        return $this->hasMany(InventoryImage::class, 'inventory_id', 'id');
    }

    public function inventoryVariants()
    {
        return $this->hasMany(InventoryVariant::class, 'inventory_id', 'id');
    }
}

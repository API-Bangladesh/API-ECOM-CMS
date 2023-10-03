<?php

namespace Modules\Combo\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\Inventory;
use Modules\Inventory\Entities\InventoryVariant;
use Modules\Base\Entities\BaseModel;

class ComboItem extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id','combo_id','quantity','inventory_id','created_at','updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id')->with('product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryVariants()
    {
        return $this->hasMany(InventoryVariant::class, 'inventory_id', 'id')->with('variant', 'variantOption');
    }

    protected static function newFactory()
    {
        return \Modules\Combo\Database\factories\ComboItemFactory::new();
    }
}

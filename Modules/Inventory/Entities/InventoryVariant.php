<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Variant\Entities\Variant;
use Modules\VariantOption\Entities\VariantOption;

class InventoryVariant extends Model
{
    use HasFactory;
    protected $table='inventory_variants';
    protected $fillable = ['inventory_id','variant_id','variant_option_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantOption()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\factories\InventoryFactory::new();
    }

    public function getVariantName(){
        return $this->hasOne(Variant::class,'variant_id','variant_id');
    }

    public function getVariantOptionName(){
        return $this->hasOne(VariantOption::class,'variant_option_id','variant_option_id');
    }
}

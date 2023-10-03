<?php

namespace Modules\Wishlist\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\BaseModel;
use Modules\Customer\Entities\Customer;
use Modules\Inventory\Entities\Inventory;

class Wishlist extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'wishlist';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Wishlist\Database\factories\WishlistFactory::new();
    }
}

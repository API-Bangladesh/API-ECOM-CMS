<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customers\Entities\Customers;
use Modules\Location\Entities\District;
use Modules\Location\Entities\Division;

class Address extends Model
{
    use HasFactory;

    protected $table ='addresses';
    protected $fillable = ['title','name','address_line_1','address_line_2','division_id','district_id',
        'upazila_id','postcode','phone','customer_id','is_default_billing','is_default_shipping','status'
        ];

    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\AddressFactory::new();
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
}

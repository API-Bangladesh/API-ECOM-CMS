<?php

namespace Modules\Location\Entities;
use Modules\Base\Entities\BaseModel;

class District extends BaseModel
{
    protected $table = 'districts';
    protected $fillable = [
      'division_id','name','bn_name','lat','lon','url'
    ];
}

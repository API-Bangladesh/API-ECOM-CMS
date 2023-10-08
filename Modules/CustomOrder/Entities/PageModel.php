<?php

namespace Modules\CustomOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageModel extends BaseModel
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = ['id', 'page', 'created_at', 'updated_at'];

    protected static function newFactory()
    {
        return \Modules\CustomOrder\Database\factories\PageFactory::new();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryImage extends Model
{
    use HasFactory;
    protected $table = 'inventory_images';
    protected $fillable = ['inventory_id','image','status'];

}

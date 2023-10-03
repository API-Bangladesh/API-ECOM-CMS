<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = ['name','retail_code','owner_name','postal_code','address','zone','sales_person','phone','division','district','lat','long','status','created_by','updated_by'];
}

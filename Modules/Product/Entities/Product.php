<?php

namespace Modules\Product\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Category\Entities\Category;
use Modules\Inventory\Entities\Inventory;
use Modules\PackType\Entities\PackType;
use Modules\ProductImage\Entities\ProductImage;
use Modules\Segment\Entities\Segment;
use Modules\SubCategory\Entities\SubCategory;
use Modules\Variant\Entities\Variant;
use Modules\VariantOption\Entities\VariantOption;


class Product extends BaseModel
{
    protected $fillable = ['name', 'image','lifestyle_image','product_brochure','category_id', 'sub_category_id', 'variant_id', 'variant_option_id', 'segment_id', 'pack_id','product_link', 'product_bncn', 'product_video_path', 'product_short_desc',
     'product_long_desc', 'status','product_order', 'created_by', 'updated_by'];

    public function inventory(){
        return $this->hasMany(Inventory::class,'id','product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class,'variant_id','id');
    }
    public function variantOption()
    {
        return $this->belongsTo(VariantOption::class,'variant_option_id','id');
    }
    public function segment()
    {
        return $this->belongsTo(Segment::class,'segment_id','id');
    }
    public function pack()
    {
        return $this->belongsTo(PackType::class,'pack_id','id');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }



     protected $name;
     protected $sub_category_id;
     protected $category_id;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setSubCategory($sub_category_id)
    {
        $this->sub_category_id = $sub_category_id;
    }
    public function setCategory($category_id)
    {
        $this->category_id = $category_id;
    }

    private function get_datatable_query()
    {
        if(permission('product-bulk-delete')){
            $this->column_order = [null,'id','name', 'image','lifestyle_image','product_brochure', 'category_id', 'sub_category_id', 'variant_id', 'variant_option_id', 'segment_id', 'pack_id','product_link', 'product_bncn', 'product_video_path', 'product_short_desc','product_long_desc', 'status','product_order',null];
        }else{
            $this->column_order = ['id','name', 'image','lifestyle_image','product_brochure', 'category_id', 'sub_category_id', 'variant_id', 'variant_option_id', 'segment_id', 'pack_id','product_link', 'product_bncn', 'product_video_path', 'product_short_desc','product_long_desc', 'status','product_order',null];
        }

        $query = self::with('category','subCategory','variant','variantOption','segment','pack');

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->category_id)) {
            $query->where('category_id', $this->category_id);
        }
        if (!empty($this->sub_category_id)) {
            $query->where('sub_category_id', $this->sub_category_id);
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

}

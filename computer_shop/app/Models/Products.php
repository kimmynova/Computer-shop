<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public $fillable = [
        'uuid',
        'prod_code',
        'name',
        'description',
        'image_url',
        'unit_price',
        'cate_id',
        'brands_id',
    ];
    protected $casts = [
        'image_url' => 'json',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id');
    }
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function getBrandNameAttribute()
    {
        return $this->brand ? $this->brand->brand_name : null;
    }

}

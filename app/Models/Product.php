<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'on_sale', 
        'rating', 'sold_count', 'review_count', 'price', 'lawyer_id'
    ];
    protected $casts = [
        'on_sale' => 'boolean', 
    ];

    // 与商品SKU关联
    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    //属于律师店铺
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const ON_SALE = 1;
    const OFF_SALE = 0;

    const TYPE_PERSON = 1;
    const TYPE_ORG = 2;

    public static $statusMap = [
        self::ON_SALE => '上架',
        self::OFF_SALE => '下架',
    ];

    public static $typeMap = [
        self::TYPE_PERSON => '法律顾问',
        self::TYPE_ORG => '公司法务',
    ];

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

    // 与订单关联
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

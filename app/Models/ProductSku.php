<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    const ON_SALE = 1;
    const OFF_SALE = 0;

    public static $statusMap = [
        self::ON_SALE => '上架',
        self::OFF_SALE => '下架',
    ];
    
    protected $fillable = [
        'title', 'description', 'price', 'stock', 'product_id', 'period_value', 'period_unit', 'is_delete', 'on_sale'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

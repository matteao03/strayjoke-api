<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'stock', 'product_id', 'period_value', 'period_unit', 'is_delete', 'on_sale'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}

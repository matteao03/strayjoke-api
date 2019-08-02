<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    protected $fillable = [
        'real_name', 'city', 'district', 'address', 'phone', 'avatar', 'law_number'
    ];

    //商品表
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

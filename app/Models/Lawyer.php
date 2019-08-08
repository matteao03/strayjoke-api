<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Lawyer extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'real_name', 'city', 'district', 'address', 'phone', 'avatar', 'law_number', 'status', 'province', 'org'
    ];

    //商品表
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // return ['role' => 'lawyer'];
        return [];
    }
}

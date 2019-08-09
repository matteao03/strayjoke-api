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

    //审核表
    public function LawyerChecks()
    {
        return $this->hasMany(LawyerCheck::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

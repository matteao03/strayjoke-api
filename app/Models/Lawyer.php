<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Lawyer extends Authenticatable implements JWTSubject
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PASS = 'pass';
    const STATUS_FAILED = 'failed';
    const STATUS_LOCKED = 'locked';

    public static $statusMap = [
        self::STATUS_DRAFT => '草稿',
        self::STATUS_PROCESSING => '审核中',
        self::STATUS_PASS => '通过',
        self::STATUS_FAILED => '失败',
        self::STATUS_LOCKED => '锁定',
    ];

    protected $fillable = [
        'real_name', 'city', 'district', 'address', 'phone', 'avatar', 'law_number', 'status', 'province', 'org'
    ];

    //商品表
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //标签表
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

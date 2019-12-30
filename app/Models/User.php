<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nick_name', 'phone', 'birth', 'password', 'avatar'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_normal' => 'boolean'
    ];

    // 订单
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //优惠券
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    //收藏lawyer
    public function collectLawyers()
    {
        return $this->belongsToMany(Lawyer::class, 'user_collect_lawyers', 'user_id', 'lawyer_id')
            ->withTimestamps()
            ->orderBy('user_collect_lawyers.created_at', 'desc');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    //生成随机字符串
    public static function randomNickname()
    {
        $randomNum = str_pad(random_int(10000, 9999999), 7, 0, STR_PAD_LEFT);
        $randomStr = Str::random(5);
        return $randomStr . $randomNum;
    }
}

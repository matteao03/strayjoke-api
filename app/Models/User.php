<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nick_name', 'phone', 'birth', 'password',
    ];
    
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_normal' => 'boolean'
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'user'];
    }

    //生成随机字符串
    public static function randomNickname()
    {
        $randomNum = str_pad(random_int(10000, 9999999), 7, 0, STR_PAD_LEFT);
        $randomStr = Str::random(5);
        return $randomStr.$randomNum;
    }

}

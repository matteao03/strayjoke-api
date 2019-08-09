<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SysUser extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nick_name', 'phone', 'password', 'login_name', 'email', 'sex'
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
        return [];
    }
}

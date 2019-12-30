<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * 默认加载的关联。
     *
     * @var array
     */
    protected $with = ['couponTemplate'];

    //优惠券状态
    const STATUS_NO = 'no';
    const STATUS_OK = 'ok';

    protected $fillable = [
        'coupon_id', 'user_id', 'not_before', 'not_after'
    ];

    protected $dates = ['not_before', 'not_after'];

    //用户
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    //优惠券模板
    public function couponTemplate()
    {
        return $this->belongsTo(CouponTemplate::class, 'template_id');
    }
}

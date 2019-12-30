<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponTemplate extends Model
{
    //优惠券类型
    const TYPE_DISCOUNT = 'discount';
    const TYPE_PERCENT = 'percent';

    //优惠券有效期类型
    const PERIOD_TYPE_FIXED = 'fixed';
    const PERIOD_TYPE_DELAY = 'delay';

    //优惠券发放场景
    const SCENE_TYPE_NEW = 'new';

    public static $typeMap = [
        self::TYPE_DISCOUNT => '满减券',
        self::TYPE_PERCENT => '折扣券',
    ];

    public static $periodTypeMap = [
        self::PERIOD_TYPE_FIXED => '固定日期',
        self::PERIOD_TYPE_DELAY => '领用日期'
    ];

    public static $sceneTypeMap = [
        self::SCENE_TYPE_NEW => '新用户',
    ];

    protected $fillable = [
        'name', 'type', 'period_type', 'not_before', 'not_after',
        'is_add', 'value', 'min_amount', 'total', 'used',
        'delay_day', 'scene_type'
    ];

    protected $casts = [
        'is_add' => 'boolean'
    ];

    protected $dates = ['not_before', 'not_after'];

    //优惠券
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'template_id');
    }
}

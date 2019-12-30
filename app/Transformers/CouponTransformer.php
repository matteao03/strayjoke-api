<?php

namespace App\Transformers;

use App\Models\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['couponTemplate'];

    public function transform(Coupon $coupon)
    {
        return [
            'id' => $coupon->id,
            'notBefore' => (string) $coupon->not_before,
            'notAfter' => (string) $coupon->not_after,
            'status' => $coupon->not_after >= now()
        ];
    }

    public function includeCouponTemplate(Coupon $coupon)
    {
        $temp = $coupon->couponTemplate;
        return $this->item($temp, new CouponTemplateTransformer());
    }
}

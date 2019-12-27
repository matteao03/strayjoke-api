<?php

namespace App\Transformers\Admin;

use App\Models\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract
{
    public function transform(Coupon $coupon)
    {
        return [
            'id' => $coupon->id,
            'name' => $coupon->name,
            'updatedTime' => (string) $coupon->updated_at
        ];
    }
}

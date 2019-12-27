<?php

namespace App\Listeners;

use App\Models\Coupon;
use Carbon\Carbon;
use App\Events\CouponNew;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiveNewCoupon
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CouponNew  $event
     * @return void
     */
    public function handle(CouponNew $event)
    {
        $coupons = Coupon::where('scene_type', Coupon::SCENE_TYPE_NEW)->get();
        foreach ($coupons as $coupon) {
            //判断是否有余量
            if ($coupon->total <= $coupon->used) {
                continue;
            }
            //填充优惠券期限
            if ($coupon->period_type === Coupon::PERIOD_TYPE_FIXED) {
                //判断是否过期
                if ($coupon->not_after <= Carbon::now()) {
                    continue;
                }
                $notBefore = $coupon->not_before;
                $notAfter = $coupon->not_after;
            } else {
                $days = $coupon->delay_day;
                $notBefore = now();
                $notAfter = date("Y-m-d H:i:s", strtotime("+$days days", time()));
            }

            $event->user->coupons()->attach($coupon->id, [
                'not_before' => $notBefore,
                'not_after' => $notAfter,
                'status' => Coupon::STATUS_OK
            ]);
        }
    }
}

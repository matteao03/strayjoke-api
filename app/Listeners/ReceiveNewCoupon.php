<?php

namespace App\Listeners;

use App\Models\CouponTemplate;
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
        $templates = CouponTemplate::where('scene_type', CouponTemplate::SCENE_TYPE_NEW)->get();
        foreach ($templates as $template) {
            //判断是否有余量
            if ($template->total <= $template->used) {
                continue;
            }
            //填充优惠券期限
            if ($template->period_type === CouponTemplate::PERIOD_TYPE_FIXED) {
                //判断是否过期
                if ($template->not_after <= Carbon::now()) {
                    continue;
                }
                $notBefore = $template->not_before;
                $notAfter = $template->not_after;
            } else {
                $days = $template->delay_day;
                $notBefore = now();
                $notAfter = date("Y-m-d H:i:s", strtotime("+$days days", time()));
            }

            $event->user->coupons()->attach($template->id, [
                'not_before' => $notBefore,
                'not_after' => $notAfter,
            ]);
        }
    }
}

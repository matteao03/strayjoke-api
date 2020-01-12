<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidSendSms
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
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $order = $event->order;
        $product = Product::find($order->product_id);
        $lawyer = $product->lawyer->phone;
        $user = User::find($order->user_id);
        try {
            app('easysms')->send($user->phone, [
                'template' => 'SMS_182405411',
                'data' => [
                    'name' => $user->name,
                    'product' => $product->title,
                    'start' => $order->start_at,
                    'end' => $order->end_at,
                    'no' => $order->no
                ],
            ]);
            app('easysms')->send($user->phone, [
                'template' => 'SMS_182415403',
                'data' => [
                    'name' => $lawyer->name,
                    'phone' => $user->phone,
                    'product' => $product->title,
                    'start' => $order->start_at,
                    'end' => $order->end_at,
                    'no' => $order->no
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
        }
    }
}

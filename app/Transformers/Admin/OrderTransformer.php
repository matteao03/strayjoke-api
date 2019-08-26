<?php

namespace App\Transformers\Admin;

use App\Models\Order;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'amount' => $order->total_amount,
            'createdTime' => (string)$order->created_at,
            'updatedTime' => (string)$order->updated_at,
            'user' => User::find($order->user_id)->nick_name
        ];
    }
}
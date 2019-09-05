<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'amount' => $order->total_amount,
            'createdTime' => $order->created_at,
            'updatedTime' => $order->updated_at,
        ];
    }
}
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
            'amount' => $order->total_amount,
            'created_at' => (string) $order->created_at,
            'updated_at' => (string) $order->updated_at,
        ];
    }
}
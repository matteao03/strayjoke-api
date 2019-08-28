<?php

namespace App\Transformers\Lawyer;

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
            'createdTime' => (string)$order->created_at,
            'updatedTime' => (string)$order->updated_at,
        ];
    }
}
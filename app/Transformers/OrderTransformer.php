<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['sku'];

    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'amount' => $order->total_amount,
            'paid_at' => $order->paid_at,
            'closed' => $order->closed,
            'createdTime' => $order->created_at,
            'updatedTime' => $order->updated_at,
        ];
    }

    public function includeSku(Order $order)
    {
        $temp = $order->sku;
        return $this->item($temp, new ProductSkuTransformer());
    }
}

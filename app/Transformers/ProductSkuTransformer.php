<?php

namespace App\Transformers;

use App\Models\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract
{
    public function transform(ProductSku $sku)
    {
        return [
            'id' => $sku->id,
            'title' => $sku->title,
            'desc' => $sku->description,
            'status' => $sku->on_sale,
            'price' => $sku->price,
            'period' => $sku->period_value,
            'unit' => $sku->period_unit,
            'created_at' => $sku->created_at,
            'updated_at' => $sku->updated_at,
        ];
    }
}


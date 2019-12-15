<?php

namespace App\Http\Requests\Web;

use App\Models\ProductSku;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'skuId' => [
                'required', function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('该商品不存在');
                    }
                    if (!$sku->product->on_sale) {
                        return $fail('该商品未上架');
                    }
                }
            ],
            'start_at' => ['required', 'date']
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['skus', 'lawyer'];

    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'desc' => $product->description,
            'type' => $product->type,
            'isSale' => $product->on_sale,
            'price' => $product->price,
            'reviewCount' => $product->review_count,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    public function includeSkus(Product $product)
    {
        $skus = $product->skus;
        return $this->collection($skus, new ProductSkuTransformer());
    }

    public function includeLawyer(Product $product)
    {
        return $this->item($product->lawyer, new LawyerTransformer());
    }
}

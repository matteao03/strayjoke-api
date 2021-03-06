<?php

namespace App\Transformers\Admin;

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
            'isSale' => $product->on_sale,
            'price' => $product->price,
            'review' => $product->review_count,
            'createdTime' =>(string) $product->created_at,
            'updatedTime' =>(string) $product->updated_at,
        ];
    }

    public function includeSkus(Product $product)
    {
        return $this->collection($product->skus, new ProductSkuTransformer());
    }
    
    public function includeLawyer(Product $product)
    {
        return $this->item($product->lawyer, new LawyerTransformer());
    }
}
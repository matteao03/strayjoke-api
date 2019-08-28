<?php

namespace App\Http\Controllers\Lawyer;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSku;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{
    //列表
    public function index(Request $request)
    {
        $products = auth('lawyer')->user()->products;
        return $this->response->collection($products, new ProductTransformer());
    }

    //更新
    public function update(Request $request,  Product $product)
    {
        $product->update([
            'description' => $request->desc,
        ]);
        return $this->response->noContent();
    }
}

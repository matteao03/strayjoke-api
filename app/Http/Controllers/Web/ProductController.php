<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{
    //列表
    public function index(Request $request)
    {
        $type = $request->query('type');
        $productType = Product::$typeMap;
        $products = Product::where('type', $type)
            ->orderBy($request->query('order') ?: 'updated_at', $request->query('sort') ?: 'asc')
            ->paginate(1);
        return $this->response->paginator($products, new ProductTransformer())->addMeta('type', $productType[$type]);
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

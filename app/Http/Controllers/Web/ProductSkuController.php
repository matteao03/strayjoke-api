<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\ProductSku;
use App\Transformers\ProductSkuTransformer;

class ProductSkuController extends Controller
{
    //列表
    public function index(Request $request)
    {
        $skus = ProductSku::where('product_id', $request->query('product'))->get();
        return $this->response->collection($skus, new ProductSkuTransformer());
    }

    //详情
    public function detail(ProductSku $sku)
    {
        return $this->response->item($sku, new ProductSkuTransformer());
    }

}

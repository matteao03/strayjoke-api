<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductSku;
use App\Transformers\ProductSkuTransformer;

class ProductSkuController extends Controller
{
    //列表
    public function index()
    {
        return $this->response->collection(ProductSku::all(), new ProductSkuTransformer());
    }

    //创建
    public function store(Request $request)
    {
        ProductSku::create([

        ]);
    }

    //更新
    public function update(Request $request,  ProductSku $sku)
    {
        $sku->update([
            'period_unit' => $request->unit,
            'period_value' => $request->period,
            'description' => $request->desc,
            'price' => (int)$request->price,
            'on_sale' => $request->status
        ]);
        return $this->response->noContent();
    }

    //删除
    public function delete(Request $request,  ProductSku $sku)
    {
        $sku->update(['is_deleted' => 1]);
        return $this->response->noContent();
    }
}

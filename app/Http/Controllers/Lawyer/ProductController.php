<?php

namespace App\Http\Controllers\Lawyer;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSku;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{
    //列表
    public function index()
    {
        return $this->response->collection(Product::all(), new ProductTransformer());
    }

    //创建
    public function store(Request $request)
    {
        \DB::transaction(function() use ($request) {
            $skus = $request->skus;
            $price = 0;
            if ($skus){
                foreach($skus as $k => $sku){
                    $arr_price = [];
                    $arr_price[$k]  = $sku['price'];
                };
                $price = min($arr_price);
            }

            $product = Product::create([
                'title'=> $request->title,
                'on_sale'=> $request->isSale,
                'description'=> $request->desc,
                'lawyer_id'=> 1,
                'price' => $price
            ]);
    
            if ($skus){
                foreach($skus as $sku){
                    ProductSku::create([
                        'title'=>$sku['title'],
                        'description'=>$sku['desc'],
                        'price'=>$sku['price'],
                        'product_id'=> $product->id,
                        'stock'=>100
                    ]);
                };
            }
        });

        return $this->response->created();
    }

    //更新

    //删除
}

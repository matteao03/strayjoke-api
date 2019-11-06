<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Lawyer;
use App\Models\ProductSku;
use App\Transformers\LawyerTransformer;
use App\Transformers\ProductSkuTransformer;

class CollectController extends Controller
{
    
    //收藏
    public function collectLawyer(Lawyer $lawyer)
    {
        $user = auth('user')->user();
        if ($user->collectLawyers()->find($lawyer->id)) {
            return $this->response->noContent();
        }

        $user->collectLawyers()->attach($lawyer);

        return $this->response->noContent();
    }

    //取消收藏
    public function uncollectLawyer(Lawyer $lawyer)
    {
        $user = auth('user')->user();
        $user->collectLawyers()->detach($lawyer);

        return $this->response->noContent();
    }

    //收藏
    public function collectSku(ProductSku $sku)
    {
        $user = auth('user')->user();
        if ($user->collectSkus()->find($sku->id)) {
            return $this->response->noContent();
        }

        $user->collectSkus()->attach($sku);

        return $this->response->noContent();
    }

    //取消收藏
    public function uncollectSku(ProductSku $sku)
    {
        $user = auth('user')->user();
        $user->collectSkus()->detach($sku);

        return $this->response->noContent();
    }

    //列表
    public function indexLawyers(Request $request)
    {
        $user = auth('user')->user();
        return $this->response->collection($user->collectLawyers, new LawyerTransformer());
    }
    //列表
    public function indexSkus(Request $request)
    {
        $user = auth('user')->user();
        return $this->response->collection($user->collectSkus, new ProductSkuTransformer());
    }
}

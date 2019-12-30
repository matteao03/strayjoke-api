<?php

namespace App\Http\Controllers\Web;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Transformers\CouponTransformer;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('user')->user();
        $type = $request->type === Coupon::STATUS_NO ? '<' : '>=';
        $coupons = $user->coupons()
            ->where('not_after', $type, now())->get();
        return $this->response->collection($coupons, new CouponTransformer());
    }
}

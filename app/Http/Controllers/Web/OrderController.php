<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\Web\OrderRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Transformers\OrderTransformer;
use App\Transformers\ProductSkuTransformer;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function preStore(ProductSku $sku)
    {
        $user = auth('user')->user();
        //查找优惠券
        $coupon = $user->coupons()->where('status', Coupon::STATUS_OK)
            ->orderBy('not_after', 'asc')->first();

        return $this->response->array(['coupon' => $coupon->toArray(), 'sku' => $sku->toArray()]);
    }

    public function store(OrderRequest $request)
    {
        $user = auth('user')->user();

        //开启一个数据库事务
        $order = \DB::transaction(function () use ($user, $request) {
            $sku = ProductSku::find($request->skuId);
            $totalAmount = $sku->price;
            if ($couponId = $request->couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon->type === Coupon::TYPE_DISCOUNT) {
                    if ($totalAmount > $coupon->value) {
                        $totalAmount = $totalAmount - $coupon->value;
                    } else {
                        $totalAmount = 0;
                    }
                } else {
                    $totalAmount = $totalAmount * $coupon->value;
                }
            }

            $order = new Order([
                'remark' => $request->remark,
                'total_amount' => $totalAmount,
                'start_at' => $request->start_at,
                'end_at' => time(),
                'sku_id' => $sku->id,
                'product_id' => $sku->product->id,
                'coupon_id' => $couponId
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            return $order;
        });

        return $order;
    }

    public function index(Request $request)
    {
        $query = $request->query('status') ?: 'all';
        if ($query === 'all') {
            $orders = auth('user')->user()->orders;
        } else if ($query === 'nopay') {
            $orders = auth('user')->user()->orders()->whereNull('paid_at')->get();
        } else if ($query === 'paid') {
            $orders = auth('user')->user()->orders()->whereNotNull('paid_at')->get();
        } else if ($query === 'nocomment') {
            $orders = auth('user')->user()->orders()->whereNotNull('paid_at')->get();
        } else if ($query === 'refund') {
            $orders = auth('user')->user()->orders()->whereNotNull('refund_status')->get();
        }
        return $this->response->collection($orders, new OrderTransformer());
    }
}

<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\Web\OrderRequest;
use App\Models\Coupon;
use App\Models\CouponTemplate;
use App\Models\Order;
use App\Models\ProductSku;
use App\Transformers\OrderTransformer;
use App\Jobs\CloseOrder;

class OrderController extends Controller
{
    public function preStore(ProductSku $sku)
    {
        $user = auth('user')->user();
        //查找优惠券
        $coupon = $user->coupons()->where('not_after', '>=', now())
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
                $template = Coupon::find($couponId)->couponTemplate;
                if ($template->type === CouponTemplate::TYPE_DISCOUNT) {
                    if ($totalAmount > $template->value) {
                        $totalAmount = $totalAmount - $template->value;
                    } else {
                        $totalAmount = 0;
                    }
                } else {
                    $totalAmount = $totalAmount * $template->value;
                }
            }

            // $start_at = $request->start_at;

            $start_at = date('Y-m-s H:i:s', strtotime($request->start_at));
            //服务结束时间
            $end_at = date("Y-m-d H:i:s", strtotime("+$sku->period_value days", strtotime($start_at)));

            $order = new Order([
                'remark' => $request->remark,
                'total_amount' => $totalAmount,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'sku_id' => $sku->id,
                'product_id' => $sku->product->id,
                'coupon_id' => $couponId
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $this->dispatch(new CloseOrder($order, config('app.order_ttl')));

            return $order;
        });

        return $order;
    }

    public function index(Request $request)
    {
        $user = auth('user')->user();
        $query = $request->query('type') ?: 'all';
        $size = $request->query('size') ?: 10;
        $page = $request->query('page') ?: 1;

        if ($query === 'all') {
            $orders = $user->orders()->paginate($size, ['*'], 'page', $page);
        } else if ($query === 'nopay') {
            $orders = $user->orders()->whereNull('paid_at')->paginate($size, ['*'], 'page', $page);
        } else if ($query === 'paid') {
            $orders = $user->orders()->whereNotNull('paid_at')->paginate($size, ['*'], 'page', $page);
        } else if ($query === 'nocomment') {
            $orders = $user->orders()->whereNotNull('paid_at')->paginate($size, ['*'], 'page', $page);
        } else if ($query === 'refund') {
            $orders = $user->orders()->whereNotNull('refund_no')->paginate($size, ['*'], 'page', $page);
        }
        return $this->response->paginator($orders, new OrderTransformer());
    }

    public function detail(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }
}

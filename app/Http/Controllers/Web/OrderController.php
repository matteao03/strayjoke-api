<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\Web\OrderRequest;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Transformers\OrderTransformer;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $user = User::find(6);
        //开启一个数据库事务
        $order = \DB::transaction(function() use ($user, $request) {
            $sku = ProductSku::find($request->skuId);

            $order = new Order([
                'remark' =>$request->remark,
                'total_amount' => $sku->price,
                'start_at' => time(),
                'end_at' => time()
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
        $query = $request->query('status');
        if ($query === 'all'){
            $orders = auth()->user()->orders;
        } else if ($query === 'nopay') {
            $orders = auth()->user()->orders()->whereNull('paid_at')->get();
        } else if ($query === 'paid') {
            $orders = auth()->user()->orders()->whereNotNull('paid_at')->get();
        } else if ($query === 'nocomment') {
            $orders = auth()->user()->orders()->whereNotNull('paid_at')->get();
        } else if ($query === 'refund') {
            $orders = auth()->user()->orders()->whereNotNull('refund_status')->get();
        }
        return $this->response->collection($orders, new OrderTransformer());
    }
}

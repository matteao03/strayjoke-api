<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\OrderComment;
use App\Models\Order;
use App\Models\Lawyer;

class OrderCommentController extends Controller
{
    //索引
    public function index(Lawyer $lawyer)
    {
        //根据律师id筛选订单评价 lawyer->product->order->comment
        // $coment = $lawyer->products->orders->comment;
        $comments = \DB::table('order_comments')
            ->join('orders', 'orders.id', '=', 'order_comments.order_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->join('lawyers', 'lawyers.id', '=', 'products.lawyer_id')
            ->select('users.nick_name', 'users.phone', 'users.avatar', 'order_comments.rating', 'order_comments.comment')
            ->where('lawyers.id', $lawyer->id)
            ->get();

        return $this->response->array($comments->toArray());
        // return ->array();
    }

    //评价
    public function store(Order $order, Request $request)
    {
        //判断是否是本人的订单
        $this->authorize('store', $order);
        //判断是否评论
        $request->validate([
            'rating' => 'required',
            'comment' => 'required',
        ]);

        //评价订单, 重新计算律师的评分和评论数
        OrderComment::create([
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
    }
}

<?php

namespace App\Http\Controllers\Lawyer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Transformers\Lawyer\OrderTransformer;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::paginate($request->query('size'));

        return $this->response->paginator($orders, new OrderTransformer());
    }
}

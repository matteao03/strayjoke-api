<?php

namespace App\Http\Controllers\Lawyer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Transformers\Lawyer\OrderTransformer;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::whereHas('product', function($query){
            $query->where('lawyer_id', auth('lawyer')->id());
        })->paginate($request->query('size'));

        return $this->response->paginator($orders, new OrderTransformer());
    }
}

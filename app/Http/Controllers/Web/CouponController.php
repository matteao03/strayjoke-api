<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('user')->user();
        return $this->response->collection();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Transformers\Admin\ProductTransformer;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate($request->query('size'));

        return $this->response->paginator($products, new ProductTransformer());
    }
}

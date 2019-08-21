<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\Admin\UserTransformer;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($phone = $request->query('phone')){
            $users = User::where('phone', 'like', '%'.$phone.'%' )->paginate($request->query('size'));
        } else {
            $users = User::paginate($request->query('size'));
        }

        return $this->response->paginator($users, new UserTransformer());
    }
}

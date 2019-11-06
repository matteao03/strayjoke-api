<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'login_name' => $request->name,
            'password' => $request->password
        ];
        if (! $token = auth('admin')->attempt($credentials))
        {
            return response()->json(['error' => '用户名和密码错误'], 401);
        }

        return $this->respondWithToken($token);
    }

    //获取管理员信息
    public function getInfo()
    {
        return response()->json(auth('admin')->user());
    }
    
     //返回token
     protected function respondWithToken($token)
     {
         return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
         ]);
     }
}

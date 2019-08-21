<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Web\AuthCodeRequest;
use App\Http\Requests\Web\AuthPasswordRequest;
use App\Http\Requests\Web\AuthSignupRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    //注册
    public function signup(AuthSignupRequest $request)
    {
        $verifyData = Cache::get('signupCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $user = User::create([
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'nick_name' => User::randomNickname()
        ]);
        
        // 清除缓存
        Cache::forget('signupCode:'.$request->phone);
        
        //自动登录
        $token = auth()->login($user);
        
        return $this->respondWithToken($token);
    }

    //密码登录
    public function loginByPassword (AuthPasswordRequest $request)
    {
        $credentials = request(['phone', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token);
    }

    //验证码登录
    public function loginByCode (AuthCodeRequest $request)
    {
        $verifyData = Cache::get('loginCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $user = User::where('phone', $request->phone)->first();
        if (!$user){
            $user = User::create([
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'nick_name' => User::randomNickname()
            ]);
        }
        
        // 清除缓存
        Cache::forget('loginCode:'.$request->phone);
        
        //自动登录
        $token = auth()->login($user);
        
        return $this->respondWithToken($token);
    }

    //获取用户信息
    public function getInfo()
    {
        $user = auth()->user();
        return $this->response->item($user, new UserTransformer());
    }

    //更改用户名
    public function updateName(Request $request)
    {
        $user = auth()->user();
        $user->update(['nick_name' => $request->name]);
        return $this->response->noContent();
    }

    //更改生日
    public function updateBrith(Request $request)
    {
        $user = auth()->user();
        $user->update(['birth' => $request->birth]);
        return $this->response->noContent();
    }

     //上传用户头像
     public function updateAvatar(Request $request)
     {
        $uploader = new ImageUploadHandler();
        $user = auth()->user();
        $image= $request->avatar;
        $path = public_path().'/image/user/avatar';
        $imageName = $user->id.'.png';

        if (!$uploader->saveBase64($image, $path, $imageName)){
            return $this->response->errorInternal();
        } 
        
        $user->update(['avatar' => '/image/user/avatar/'.$imageName]);
        return $this->response->noContent();
     }

    //退出
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    //刷新token
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    //返回token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

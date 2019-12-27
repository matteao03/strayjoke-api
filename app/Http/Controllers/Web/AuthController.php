<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Web\AuthCodeRequest;
use App\Http\Requests\Web\AuthPasswordRequest;
use App\Http\Requests\Web\AuthSignupRequest;
use App\Transformers\UserTransformer;
use App\Transformers\LawyerTransformer;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use App\Events\CouponNew;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    //注册
    public function signup(AuthSignupRequest $request)
    {
        $verifyData = Cache::get('signupCode:' . $request->phone);

        if (!$verifyData) {
            //401
            return $this->response->errorUnauthorized('验证码不存在');
        }

        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'nick_name' => User::randomNickname(),
            'avatar' => '/image/user/avatar/6.png'
        ]);

        // 清除缓存
        Cache::forget('signupCode:' . $request->phone);

        //分发优惠券
        event(new CouponNew($user));

        //自动登录
        $token = auth('user')->login($user);

        return $this->respondWithToken($token);
    }

    //密码登录
    public function loginByPassword(AuthPasswordRequest $request)
    {
        $credentials = request(['phone', 'password']);

        if (!$token = auth('user')->attempt($credentials)) {
            return  $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token);
    }

    //验证码登录
    public function loginByCode(AuthCodeRequest $request)
    {
        $verifyData = Cache::get('loginCode:' . $request->phone);

        if (!$verifyData) {
            return $this->response->errorUnauthorized('验证码不存在');
        }

        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            $user = User::create([
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'nick_name' => User::randomNickname(),
                'avatar' => ''
            ]);
        }

        // 清除缓存
        Cache::forget('loginCode:' . $request->phone);

        //自动登录
        $token = auth('user')->login($user);

        return $this->respondWithToken($token);
    }

    //验证找回密码
    public function verifyForgetCode(AuthCodeRequest $request)
    {
        $verifyData = Cache::get('forgetCode:' . $request->phone);


        if (!$verifyData) {
            //缓存
            Cache::put('verifyForgetCode:' . $request->phone, 0, now()->addMinutes(5));
            //401
            return $this->response->errorUnauthorized('验证码已失效');
        }

        if (!hash_equals($verifyData, $request->verifyCode)) {
            //缓存
            Cache::put('verifyForgetCode:' . $request->phone, 0, now()->addMinutes(5));
            //401
            return $this->response->errorUnauthorized('验证码错误');
        }

        // 清除缓存
        Cache::forget('forgetCode:' . $request->phone);
        //缓存
        Cache::put('verifyForgetCode:' . $request->phone, 1, now()->addMinutes(5));

        return $this->response->noContent();
    }

    //重置密码
    public function resetPassword(AuthPasswordRequest $request)
    {
        $verifyData = Cache::get('verifyForgetCode:' . $request->phone);

        if (!$verifyData) {
            return $this->response->errorUnauthorized('验证码已失效');
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user->password =  bcrypt($request->password);
            $user->save();

            return $this->response->noContent();
        }

        //清除缓存
        Cache::forget('verifyForgetCode:' . $request->phone);
        return $this->response->error('网络出错,请重新验证', 500);
    }

    //获取用户信息
    public function getInfo()
    {
        $user = auth('user')->user();
        return $this->response->item($user, new UserTransformer());
    }

    //更改用户名
    public function updateName(Request $request)
    {
        $user = auth('user')->user();
        $user->update(['nick_name' => $request->name]);
        return $this->response->noContent();
    }

    //更改生日
    public function updateBrith(Request $request)
    {
        $user = auth('user')->user();
        $user->update(['birth' => $request->birth]);
        return $this->response->noContent();
    }

    //上传用户头像
    public function updateAvatar(Request $request)
    {
        $uploader = new ImageUploadHandler();
        $user = auth('user')->user();
        $image = $request->avatar;
        $path = public_path() . '/image/user/avatar';
        $imageName = $user->id . '.png';

        if (!$uploader->saveBase64($image, $path, $imageName)) {
            return $this->response->errorInternal();
        }

        $user->update(['avatar' => '/image/user/avatar/' . $imageName]);
        return $this->response->noContent();
    }

    public function indexLawyers(Request $request)
    {
        $user = Auth('user')->user();
        $size = $request->query('size') ?: 10;
        $page = $request->query('page') ?: 1;
        $lawyers = $user->collectLawyers()
            ->orderBy($request->query('order') ?: 'updated_at', $request->query('sort') ?: 'asc')
            ->paginate($size, ['*'], 'page', $page);
        return $this->response->paginator($lawyers, new LawyerTransformer());
    }

    //退出
    public function logout()
    {
        auth('user')->logout();

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
            'expires_in' => auth('user')->factory()->getTTL() * 60
        ]);
    }
}

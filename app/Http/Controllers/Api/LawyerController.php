<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LawyerSignupCodeRequest;
use App\Http\Requests\Api\LawyerLoginCodeRequest;
use App\Http\Requests\Api\AuthCodeRequest;
use App\Models\Lawyer;
use Illuminate\Support\Facades\Cache;

class LawyerController extends Controller
{
    public function storeSignupCode(LawyerSignupCodeRequest $request)
    {
        $phone = $request->phone;
        
        // 生成随机数
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        
        try {
            app('easysms')->send($phone, [
                'template' => 'SMS_148590449',
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('aliyun')->getMessage();
            return $this->response->errorInternal($message ?: '');
        }
        
        //缓存
        Cache::put('lawyerSignupCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }

    //获取验证码
    public function storeLoginCode(LawyerLoginCodeRequest $request)
    {
        $phone = $request->phone;
        
        // 生成随机数
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        
        try {
            app('easysms')->send($phone, [
                'template' => 'SMS_170347373',
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('aliyun')->getMessage();
            return $this->response->errorInternal($message ?: '');
        }
        
        //缓存
        Cache::put('lawyerLoginCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }

    //注册
    public function signup(AuthCodeRequest $request)
    {
        $verifyData = Cache::get('lawyerSignupCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $lawyer = Lawyer::create([
            'phone' => $request->phone,
            'status' => 1
        ]);
        
        // 清除缓存
        Cache::forget('lawyerSignupCode:'.$request->phone);
        
        //自动登录
        $token = auth()->login($lawyer);
        
        return $this->respondWithToken($token);
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

<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\SignupCodeRequest;
use App\Http\Requests\Web\LoginCodeRequest;
use App\Http\Requests\Web\ForgetCodeRequest;
use Illuminate\Support\Facades\Cache;


class VerifyCodeController extends Controller
{
    //获取注册验证码
    public function storeSignupCode(SignupCodeRequest $request)
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
        Cache::put('signupCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }

    //获取登录验证码
    public function storeLoginCode(LoginCodeRequest $request)
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
        Cache::put('loginCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }

    //获取重置验证码
    public function storeForgetCode(ForgetCodeRequest $request)
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
        Cache::put('forgetCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }
}

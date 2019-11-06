<?php

namespace App\Http\Controllers\Lawyer;

use Exception;
use App\Http\Requests\Lawyer\SignupCodeRequest;
use App\Http\Requests\Lawyer\LoginCodeRequest;
use App\Http\Requests\Lawyer\AuthCodeRequest;
use App\Http\Requests\Lawyer\AuthPasswordRequest;
use App\Http\Requests\Lawyer\AuthForgetCodeRequest;
use App\Http\Requests\Lawyer\ForgetCodeRequest;
use App\Models\Lawyer;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use App\Transformers\Lawyer\LawyerTransformer;

class LawyerController extends Controller
{
    //注册验证码
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
            return $this->response->errorInternal('短信发送失败，请重试');
        }
        
        //缓存
        Cache::put('lawyerSignupCode:'.$phone, $code, now()->addMinutes(5));
        
        return $this->response->created();
    }

    //登录验证码
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
            return $this->response->errorInternal('短信发送失败，请重试');
        }
        
        //缓存
        Cache::put('lawyerLoginCode:'.$phone, $code, now()->addMinutes(5));
        
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
         Cache::put('forgetLawyerCode:'.$phone, $code, now()->addMinutes(5));
         
         return $this->response->created();
     }

    //注册
    public function signup(AuthCodeRequest $request)
    {
        $verifyData = Cache::get('lawyerSignupCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $lawyer = \DB::transaction(function() use ($request) {
            $lawyer = Lawyer::create([
                'phone' => $request->phone,
                'status' => Lawyer::STATUS_DRAFT
            ]);

            Product::create([
                'title'=> Product::TYPE_PERSON,
                'on_sale'=> Product::ON_SALE,
                'description'=> '',
                'lawyer_id'=> $lawyer->id,
                'price' => 0
            ]);

            Product::create([
                'title'=> Product::TYPE_ORG,
                'on_sale'=> Product::ON_SALE,
                'description'=> '',
                'lawyer_id'=> $lawyer->id,
                'price' => 0
            ]);
            
            return $lawyer;
        });
        
        // 清除缓存
        Cache::forget('lawyerSignupCode:'.$request->phone);
        
        //自动登录
        $token = auth('lawyer')->login($lawyer);
        
        return $this->respondWithToken($token);
    }

    //密码登录
    public function loginByPassword (AuthPasswordRequest $request)
    {
        $credentials = request(['phone', 'password']);

        if (! $token = auth('lawyer')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token);
    }

    //验证码登录
    public function loginByCode (AuthCodeRequest $request)
    {
        $verifyData = Cache::get('lawyerLoginCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $lawyer = Lawyer::where('phone', $request->phone)->first();
        if (!$lawyer){
            return $this->response->errorUnauthorized('当前手机号未注册');
        }
        // 清除缓存
        Cache::forget('loginCode:'.$request->phone);
        
        //自动登录
        $token = auth('lawyer')->login($lawyer);
        
        return $this->respondWithToken($token);
    }

    //验证找回密码
    public function verifyForgetCode (AuthForgetCodeRequest $request)
    {
        $verifyData = Cache::get('forgetLawyerCode:'.$request->phone);
        
        
        if (!$verifyData) {
            //缓存
            Cache::put('verifyForgetLawyerCode:'.$request->phone, 0, now()->addMinutes(5));
            //401
            return $this->response->errorUnauthorized('验证码已失效');
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            //缓存
            Cache::put('verifyForgetLawyerCode:'.$request->phone, 0, now()->addMinutes(5));
            //401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        // 清除缓存
        Cache::forget('forgetLawyerCode:'.$request->phone);
        //缓存
        Cache::put('verifyForgetLawyerCode:'.$request->phone, 1, now()->addMinutes(5));
        
        return $this->response->noContent();
    }

    //重置密码
    public function resetPassword (AuthPasswordRequest $request)
    {
        $verifyData = Cache::get('verifyForgetLawyerCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->errorUnauthorized('验证码已失效');
        }
        
        $lawyer = Lawyer::where('phone', $request->phone)->first();
        if ($lawyer){
            $lawyer->password =  bcrypt($request->password);
            $lawyer->save();
            
            return $this->response->noContent();
        }

        //清除缓存
        Cache::forget('verifyForgetLawyerCode:'.$request->phone);
        return $this->response->error('网络出错,请重新验证', 500);
    }


    //退出
    public function logout()
    {
        try {
            auth('lawyer')->logout();
        } catch(Exception $e){
            return $this->response->noContent();
        }

        return $this->response->noContent();
    }

    //获取律师信息
    public function getInfo()
    {
        $payload = auth('lawyer')->payload();
        $lawyer = auth('lawyer')->user();
        
        try {
            $lawyer = auth('lawyer')->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $this->response->errorUnauthorized('会话过期，请重新登录');
        }

        return $this->response->item($lawyer, new LawyerTransformer());
    }

    //更新律师信息
    public function updateInfo(Request $request, $phone)
    {
        $lawyer = Lawyer::where('phone', $phone)->first();

        if ($lawyer){
            $lawyer->update([
                'real_name'=>$request->name,
                'law_number'=>$request->lawNumber,
                'org'=>$request->org,
                'address'=>$request->address,
                'province'=>$request->area[0],
                'city'=>$request->area[1],
                'district'=>count($request->area)=== 3 ? $request->area[2] : null,
                'avatar'=>$request->avatar,
                'status'=> Lawyer::STATUS_PROCESSING
            ]);
        }
        
        return $this->response->noContent();
    }

    //上传律师头像
    public function postAvatar(Request $request)
    {
        $uploader = new ImageUploadHandler();
        $file = $request->file('file');
        $result = $uploader->save($file, '/image/lawyer/avatar', auth('lawyer')->user()->id);
        return response()->json($result);
    }

    //返回token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('lawyer')->factory()->getTTL() * 60
        ]);
    }

}

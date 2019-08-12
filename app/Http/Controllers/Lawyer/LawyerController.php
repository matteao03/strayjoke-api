<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Requests\Lawyer\SignupCodeRequest;
use App\Http\Requests\Lawyer\LoginCodeRequest;
use App\Http\Requests\Lawyer\AuthCodeRequest;
use App\Models\Lawyer;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use Cblink\Region\Area;

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
            return $this->response->errorInternal($message ?: '');
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
            return response()->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token);
    }

    //验证码登录
    public function loginByCode (AuthCodeRequest $request)
    {
        $verifyData = Cache::get('lawyerLoginCode:'.$request->phone);
        
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        
        if (!hash_equals($verifyData, $request->verifyCode)) {
            // 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        
        $lawyer = Lawyer::where('phone', $request->phone)->first();
        if (!$lawyer){
            return $this->response->error('', 401);
        }
        // 清除缓存
        Cache::forget('loginCode:'.$request->phone);
        
        //自动登录
        $token = auth('lawyer')->login($lawyer);
        
        
        return $this->respondWithToken($token);
    }

    //退出
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'success']);
    }

    //获取律师信息
    public function getInfo()
    {
        $user = auth()->user();
        $areaText = '';
        if ($user->province){
            $areaText .= Area::where('id', $user->province)->first()->name.'/';
        }
        if ($user->city){
            $areaText .= Area::where('id', $user->city)->first()->name.'/';
        }
        if ($user->district){
            $areaText .= Area::where('id', $user->district)->first()->name;
        }
        $user->areaText = $areaText;
        return response()->json($user);
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
        $result = $uploader->save($file, '/image/lawyer/avatar', auth()->user()->id);
        return response()->json($result);
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

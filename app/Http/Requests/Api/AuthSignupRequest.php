<?php

namespace App\Http\Requests\Api;

class AuthSignupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string',
            'verifyCode' => 'required|string',
        ];
    }
    
    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.unique' => '手机号已注册',
            'password.required' => '密码不能为空',
            'verifyCode.required' => '验证码不能为空',
        ];
    }
}
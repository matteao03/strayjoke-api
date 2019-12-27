<?php

namespace App\Http\Requests\Web;

class AuthSignupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'unique:users,phone',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
            ],
            'password' => 'required|string',
            'verifyCode' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.unique' => '手机号已注册',
            'phone.regex' => '手机号格式不正确',
            'password.required' => '密码不能为空',
            'verifyCode.required' => '验证码不能为空',
        ];
    }
}

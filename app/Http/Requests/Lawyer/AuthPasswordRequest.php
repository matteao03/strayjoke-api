<?php

namespace App\Http\Requests\Lawyer;

class AuthPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
            ],
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'password.required' => '密码不能为空',
        ];
    }
}

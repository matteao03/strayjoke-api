<?php

namespace App\Http\Requests\Web;

class SignupCodeRequest extends FormRequest
{
    /**
     * 验证规则
     * @return
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                'unique:users,phone'
            ]
        ];
    }
    
    /**
     * 
     * 自定义错误信息
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.regex'  => '手机号格式不正确',
            'phone.unique'  => '手机号已注册，请登录或找回密码',
        ];
    }
}

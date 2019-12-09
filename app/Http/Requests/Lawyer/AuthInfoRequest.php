<?php

namespace App\Http\Requests\Lawyer;

class AuthInfoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' =>'required|string',
            'lawNumber'=> 'required|string',
            'org'=>'required|string',
            'area'=>'required | array',
            'address'=> 'required|string',
            'good' => 'required | array',
            'file'=> 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '真实姓名不能为空',
            'name.string' => '真实姓名填写格式有误',
            'lawNumber.required' => '执业证号不能为空',
            'lawNumber.string' => '执业证号填写格式有误',
            'org.required' => '执业机构不能为空',
            'org.string' => '执业机构填写格式有误',
            'address.required' => '执业机构详细地址不能为空',
            'address.string' => '执业机构详细地址填写格式有误',
            'file.required' => '头像不能为空',
            'file.string' => '头像上传有误',
            'area.array' => '执业机构区域选择不正确',
            'area.required' => '执业机构区域不能为空',
            'good.array' => '擅长领域选择不正确',
            'good.required' => '擅长领域不能为空'
        ];
    }
}

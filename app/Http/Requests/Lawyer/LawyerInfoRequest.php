<?php

namespace App\Http\Requests\Lawyer;

class LawyerInfoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'lawNumber' => 'required|string',
            'org' => 'required|string',
            'address' => 'required|string',
            'area' => 'required|array',
            'avatar' => 'required|string',
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
            'name.required' => '真实姓名填写不正确',
            'lawNumber.required' => '执业证号填写不正确',
            'org.required' => '执业机构填写不正确',
            'address.required' => '执业机构详细地址填写不正确',
            'area.required' => '执业机构区域填写不正确',
            'area.array' => '执业机构区域填写不正确',
            'avatar.required' => '工作照上传有误',
        ];
    }
}

<?php
namespace App\Handlers;

class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg', 'bmp'];

    public function save($file, $path, $filename)
    {
        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID 
        $filename = $filename . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move(public_path()."$path/", $filename);

        return [
            'data' =>[
                'path' => "$path/$filename"
            ]
        ];
    }
}

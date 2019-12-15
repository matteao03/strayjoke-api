<?php

namespace App\Http\Controllers\Web;

use App\Models\Lawyer;
use App\Transformers\LawyerTransformer;

class LawyerController extends Controller
{
    public function detail(Lawyer $lawyer)
    {
        return $this->response->item($lawyer, new LawyerTransformer());
    }

    //收藏
    public function collect(Lawyer $lawyer)
    {
        //判断是否登录
        $user = auth('user')->user();
        //判断是否已收藏
        if ($user->collectLawyers()->find($lawyer->id)) {
            return $this->response->noContent();
        }

        $user->collectLawyers()->attach($lawyer);

        return $this->response->noContent();
    }

    //取消收藏
    public function uncollect(Lawyer $lawyer)
    {
        $user = auth('user')->user();
        $user->collectLawyers()->detach($lawyer);

        return $this->response->noContent();
    }
}

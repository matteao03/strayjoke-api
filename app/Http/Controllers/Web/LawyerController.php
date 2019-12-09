<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Lawyer;

class LawyerController extends Controller
{

    //收藏
    public function collect(Lawyer $lawyer)
    {
        $user = auth('user')->user();
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

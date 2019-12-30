<?php

namespace App\Http\Controllers\Admin;

use App\Models\CouponTemplate;
use Illuminate\Http\Request;
use App\Transformers\Admin\CouponTemplateTransformer;

class CouponTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = CouponTemplate::paginate($request->query('size'));

        return $this->response->paginator($templates, new CouponTemplateTransformer());
    }


    //创建
    public function store(Request $request)
    {
        CouponTemplate::create([
            'name' => $request->name,
            'type' =>  $request->type,
            'period_type' =>  $request->periodType,
            'scene_type' =>  $request->sceneType,
            'value' =>  $request->value,
            'min_amount' =>  $request->minAmount,
            'not_after' =>  $request->notAfter,
            'not_before' =>  $request->notBefore,
            'delay_day' =>  $request->delayDay ?: 0,
            'total' => $request->total,
            'is_add' => $request->isAdd
        ]);

        return $this->response->noContent();
    }

    //更新
    public function update(Request $request,  CouponTemplate $template)
    {
        $template->update([]);
        return $this->response->noContent();
    }

    //删除
    public function delete(CouponTemplate $template)
    {
        $template->delete();
        return $this->response->noContent();
    }
}

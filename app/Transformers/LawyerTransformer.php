<?php

namespace App\Transformers;

use App\Models\Lawyer;
use League\Fractal\TransformerAbstract;
use Cblink\Region\Area;
use Dingo\Api\Auth\Auth;

class LawyerTransformer extends TransformerAbstract
{
    public function transform(Lawyer $lawyer)
    {
        $areaText = '';
        if ($lawyer->province) {
            $areaText .= Area::where('id', $lawyer->province)->first()->name;
        }
        if ($lawyer->city) {
            $areaText .= Area::where('id', $lawyer->city)->first()->name;
        }
        if ($lawyer->district) {
            $areaText .= Area::where('id', $lawyer->district)->first()->name;
        }

        return [
            'id' => $lawyer->id,
            'name' => $lawyer->real_name,
            'status' => $lawyer->status,
            'phone' => $lawyer->phone,
            'avatar' => 'http://api.strayjoke.test' . $lawyer->avatar,
            'lawNo' => $lawyer->law_number,
            'org' => $lawyer->org,
            'rating' => $lawyer->rating,
            'reviewCount' => $lawyer->review_count,
            'areaText' => $areaText,
            'address' => $lawyer->address,
            'comment' => $lawyer->comment,
            'createdTime' => $lawyer->created_at,
            'updatedTime' => $lawyer->updated_at,
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\LawyerCheck;
use App\Models\SysUser;
use League\Fractal\TransformerAbstract;

class LawyerCheckTransformer extends TransformerAbstract
{
    public function transform(LawyerCheck $lawyerCheck)
    {
        return [
            'id' => $lawyerCheck->id,
            'checkedBy' => SysUser::find($lawyerCheck->checked_by),
            'status' => $lawyerCheck->status,
            'content' => $lawyerCheck->content,
            'updatedTime' => $lawyerCheck->updated_at,

        ];
    }
}


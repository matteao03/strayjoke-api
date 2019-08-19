<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'nickName' => $user->nick_name,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'birth' => (string) $user->birth,
        ];
    }
}


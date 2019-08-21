<?php

namespace App\Transformers\Admin;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->nick_name,
            'phone' => $user->phone,
            'birth' => $user->birth,
            'updatedTime' => (string)$user->updated_at,
            'status' => $user->is_normal,
            'avatar' => $user->avatar,
        ];
    }
}


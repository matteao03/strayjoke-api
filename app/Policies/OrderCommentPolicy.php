<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderCommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 
     * 判断是否可以评价订单
     * 
     */
    public function store(User $user, Order $order)
    {
        return $user->id === $order->user_id && $order->paid_at;
    }
}

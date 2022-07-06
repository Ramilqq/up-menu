<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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

    static function requestShow($order_id) {
        return Order::userOrderAndProject($order_id);
    }

    static function requestGet($project_id) {
        return Order::userAndProject($project_id);
    }

    static function requestCreate($project_id) {
        return Order::userAndProject($project_id);
    }

    static function requestUpdate($order_id) {
        return Order::userOrderAndProject($order_id);
    }

    static function requestDelete($order_id) {
        return Order::userOrderAndProject($order_id);
    }

    static function requestAssignee($order_id) {
        return Order::userOrderAndProject($order_id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const NEW = 'new';
    const WORK = 'work';
    const CLOSE = 'close';
    const PAID = 'paid';

    protected $fillable = [
        'project_id',
        'table_id',
        'user_id',
        'status',
    ];

    static function userAndProject($project_id)
    {
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function userOrderAndProject($order_id)
    {
        $order = Order::find($order_id) ?: null;
        if (!$order) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $order->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $order->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function getOrderDishes($order_id)
    {
        if (!$dishes = OrderDishe::query()->where('order_id', $order_id)->select('dishe_id')->get()->toArray()) return [];
        return Dishe::query()->whereIn('id', $dishes)->get()->toArray() ?: [];
    }
}

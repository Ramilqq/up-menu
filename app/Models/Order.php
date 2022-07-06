<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const NEW = 'новый';
    const WORK = 'в работе';
    const CLOSE = 'закрыт';
    const PAID = 'оплачен';

    protected $fillable = [
        'project_id',
        'table_id',
        'user_id',
        'name',
        'status',
        'sum',
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
}

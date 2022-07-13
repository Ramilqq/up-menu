<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderUpdateRequest;
use App\Http\Requests\OrderDishe\OrderDisheRequest;
use App\Models\Dishe;
use App\Models\Order;
use App\Models\OrderDishe;
use App\Models\ProjectUser;
use App\Models\User;
use App\Policies\OrderPolicy;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($id)
    {
        if (!OrderPolicy::requestShow($id)) return response()->json([]);
        $order = Order::find($id) ?: ['success' => false, 'message' => 'Заказ не найден.'];
        return response()->json($order);
    }

    public function destroy($id)
    {
        if (!OrderPolicy::requestDelete($id)) return response()->json([]);
        $resault = Order::destroy($id);
        return response()->json(['success' => (bool) $resault]);
    }

    public function update(OrderUpdateRequest $request, $id)
    {
        $order = Order::find($id) ?: [];
        if (!$order)
        {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $order->fill($data);
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Заказ обновлен.'
        ]);
    }

    public function replace(OrderUpdateRequest $request, $id)
    {
        $order = Order::find($id) ?: [];
        if (!$order)
        {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден.'
            ]);
        }

        $data = $request->validated();

        $order->fill($data);
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Заказ обновлен.'
        ]);
    }

    public function assignee($id)
    {
        if (!OrderPolicy::requestAssignee($id)) return response()->json([]);
        if (!$order = Order::find($id))
        {
            return response()->json(['success' => false, 'message' => 'Заказ не найден.']);
        }
        $order->user_id = request()->user()->id;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Заказ назначен на вас.']);
    }

    public function assigneeTo($id, $user_id)
    {
        if (!OrderPolicy::requestAssignee($id)) return response()->json([]);
        if (!$order = Order::find($id))
        {
            return response()->json(['success' => false, 'message' => 'Заказ не найден.']);
        }
        if (!$user = User::find($user_id))
        {
            return response()->json(['success' => false, 'message' => 'Сотрудник не найден.']);
        }
        if (!$project_user = ProjectUser::query()->where('user_id', $user->id)->where('project_id', $order->project_id)->first() )
        {
            return response()->json(['success' => false, 'message' => 'Сотрудник не найден.']);
        }
        $order->user_id = $user->id;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Заказ назначен на ' . $user->first_name .'.']);
    }

    public function getDisheOrder($id)
    {
        if (!OrderPolicy::requestGet($id)) return response()->json([]);
        $dishes = Order::getOrderDishes($id);
        return response()->json($dishes);
    }

    public function destroyDisheOrder(OrderDisheRequest $request, $id)
    {
        if (!OrderPolicy::requestGet($id)) return response()->json([]);
        $data = $request->validated();
        foreach ($data['dishes_id'] as $dish)
        {
            $dishes[] = (bool) OrderDishe::query()->where('dishe_id', $dish)->where('order_id', $id)->delete();
        }
        return response()->json(['success' => true, 'message' => 'Заказ обновлен', 'data' => $dishes]);
    }

    public function updateDisheOrder(OrderDisheRequest $request, $id)
    {
        if (!OrderPolicy::requestUpdate($id)) return response()->json([]);
        $data = $request->validated();
        foreach ($data['dishes_id'] as $dish)
        {
            $dishes[] = OrderDishe::create(['order_id'=> $id, 'dishe_id'=> $dish]);
        }
        return response()->json(['success' => true, 'message' => 'Заказ обновлен', 'data' => $dishes]);
    }

}

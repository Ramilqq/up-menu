<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderUpdateRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::find($id) ?: ['success' => false, 'message' => 'Заказ не найден.'];
        return response()->json($order);
    }

    public function destroy($id)
    {
        return Order::destroy($id);
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
        if (!$order = Order::find($id))
        {
            return response()->json(['success' => false, 'message' => 'Заказ не найден.']);
        }
        if (!$user = User::find($user_id))
        {
            return response()->json(['success' => false, 'message' => 'Сотрудник не найден.']);
        }
        $order->user_id = $user->id;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Заказ назначен на вас.' . $user->first_name .'.']);
    }
}

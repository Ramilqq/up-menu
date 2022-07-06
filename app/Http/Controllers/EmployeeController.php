<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{

    public function show($id)
    {
        if (!UserPolicy::requestShow($id)) return response()->json([]);
        $user = User::find($id) ?: ['success' => false, 'message' => 'Сотрудник не найден'];
        return $user;
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id) ?: [];
        if (!$user)
        {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не найден.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        
        if ($request->has('password'))
        {
            $data['password'] = bcrypt($data['password']);
        }
        
        $data = $this->saveImage($data, $request);
        $this->deleteImageUser($user->avatar);

        $user->fill($data);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Пользователь обновлен.'
        ]);
    }

    public function replace(UserUpdateRequest $request, $id)
    {
        $user = User::find($id) ?: [];
        if (!$user)
        {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не найден.'
            ]);
        }

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data = $this->saveImage($data, $request);
        $this->deleteImageUser($user->avatar);

        if ($request->has('avatar')) {
            $images = $request->file('avatar')->storeAs('public/images/users/'.$id, 'avatar.jpg');
            $data['avatar'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['avatar']);
        }

        $user->fill($data);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Пользователь обновлен.'
        ]);
    }

    public function destroy($id)
    {
        if (!UserPolicy::requestDelete($id)) return response()->json([]);
        $user = User::find($id) ?: ['success' => false, 'message' => 'Блюдо не найдено.'];
        $this->deleteImageDishe($user->avatar);
        $resault = $user->delete();
        return response()->json(['success' => (bool) $resault]);
    }
}

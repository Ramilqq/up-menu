<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function show($id)
    {
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
        $data['password'] = bcrypt($data['password']);
        
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
            'message' => 'Пользователь обнавлен.'
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
            'message' => 'Пользователь обнавлен.'
        ]);
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}

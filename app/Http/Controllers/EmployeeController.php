<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index()
    {
        $users = User::query()->where('id', '>', '0')->get()->toArray();      
        return response()->json($users);
    }

    public function create(UserCreateRequest $request)
    {
        $data = $request->validated();
        $data_mail = $request->only('email', 'password');

        $data['password'] = bcrypt($data['password']);

        if (!$user = User::create($data))
        {
            return response()->json([
                    'success' => 'false'
                ], 200);
        }
        event(new \App\Events\EmployeeCreatedEvent($user, $data_mail));
        return response()->json($user, 200);
    }

    public function show($id)
    {
        $user = User::find($id) ?: ['message' => 'Сотрудник не найден'];
        return $user;
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id) ?: [];
        if (!$user)
        {
            return response()->json([
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
            'message' => 'Пользователь обнавлен.'
        ]);
    }

    public function replace(UserUpdateRequest $request, $id)
    {
        $user = User::find($id) ?: [];
        if (!$user)
        {
            return response()->json([
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
            'message' => 'Пользователь обнавлен.'
        ]);
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}

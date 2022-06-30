<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dishe\DisheUpdateRequest;
use App\Models\Dishe;
use Illuminate\Http\Request;

class DisheController extends Controller
{
    public function show($id)
    {
        $dishe = Dishe::find($id) ?: ['success' => false, 'message' => 'Блюдо не найдено.'];
        return response()->json($dishe);
    }

    public function destroy($id)
    {
        return Dishe::destroy($id);
    }

    public function update(DisheUpdateRequest $request, $id)
    {
        $dishe = Dishe::find($id) ?: [];
        if (!$dishe)
        {
            return response()->json([
                'success' => false,
                'message' => 'Блюдо не найдено.'
            ]);
        }

        $data = $request->validated();
        
        if ($request->has('photo')) {
            $images = $request->file('photo')->storeAs('public/images/dishe/'.$id, 'photo.jpg');
            $data['photo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['photo']);
        }

        $dishe->fill($data);
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо обнавлено.'
        ]);
    }

    public function replace(DisheUpdateRequest $request, $id)
    {
        $dishe = Dishe::find($id) ?: [];
        if (!$dishe)
        {
            return response()->json([
                'success' => false,
                'message' => 'Блюдо не найдено.'
            ]);
        }

        $data = $request->validated();
        
        if ($request->has('photo')) {
            $images = $request->file('photo')->storeAs('public/images/dishe/'.$id, 'photo.jpg');
            $data['photo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['photo']);
        }

        $dishe->fill($data);
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо обнавлено.'
        ]);
    }

    public function active($id)
    {
        $dishe = Dishe::find($id) ?: [];
        if (!$dishe)
        {
            return response()->json([
                'success' => false,
                'message' => 'Блюдо не найдено.'
            ]);
        }
        $dishe->active = 1;
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо включено.'
        ]);
    }

    public function inactive($id)
    {
        $dishe = Dishe::find($id) ?: [];
        if (!$dishe)
        {
            return response()->json([
                'success' => false,
                'message' => 'Блюдо не найдено.'
            ]);
        }
        $dishe->active = 0;
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо отключено.'
        ]);
    }
}

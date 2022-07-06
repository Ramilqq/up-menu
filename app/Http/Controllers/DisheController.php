<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dishe\DisheUpdateRequest;
use App\Models\Dishe;
use App\Policies\DishePolicy;
use Illuminate\Http\Request;

class DisheController extends BaseController
{
    public function show($id)
    {
        if (!DishePolicy::requestShow($id)) return response()->json([]);
        $dishe = Dishe::find($id) ?: ['success' => false, 'message' => 'Блюдо не найдено.'];
        return response()->json($dishe);
    }

    public function destroy($id)
    {
        if (!DishePolicy::requestDelete($id)) return response()->json([]);
        $dishe = Dishe::find($id) ?: ['success' => false, 'message' => 'Блюдо не найдено.'];
        $this->deleteImageDishe($dishe->photo);
        $resault = $dishe->delete();
        return response()->json(['success' => (bool) $resault]);
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
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $data = $this->saveImage($data, $request);
        $this->deleteImageDishe($dishe->photo);

        $dishe->fill($data);
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо обновлено.'
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
        
        $data = $this->saveImage($data, $request);
        $this->deleteImageDishe($dishe->photo);

        $dishe->fill($data);
        $dishe->save();
        return response()->json([
            'success' => true,
            'message' => 'Блюдо обновлено.'
        ]);
    }

    public function active($id)
    {
        if (!DishePolicy::requestShow($id)) return response()->json([]);
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
        if (!DishePolicy::requestShow($id)) return response()->json([]);
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

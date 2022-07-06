<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modifier\ModifierUpdateRequest;
use App\Models\Modifier;
use App\Policies\ModifierPolicy;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    public function show($id)
    {
        if (!ModifierPolicy::requestShow($id)) return response()->json([]);
        $modifier = Modifier::find($id) ?: ['success' => false, 'message' => 'Модификация не найдена.'];
        return response()->json($modifier);
    }

    public function destroy($id)
    {
        if (!ModifierPolicy::requestDelete($id)) return response()->json([]);
        return Modifier::destroy($id);
    }

    public function update(ModifierUpdateRequest $request, $id)
    {
        $modifier = Modifier::find($id) ?: [];
        if (!$modifier)
        {
            return response()->json([
                'success' => false,
                'message' => 'Модификация не найдена.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $modifier->fill($data);
        $modifier->save();
        return response()->json([
            'success' => true,
            'message' => 'Модификация обновлена.'
        ]);
    }

    public function replace(ModifierUpdateRequest $request, $id)
    {
        $modifier = Modifier::find($id) ?: [];
        if (!$modifier)
        {
            return response()->json([
                'success' => false,
                'message' => 'Модификация не найдена.'
            ]);
        }

        $data = $request->validated();

        $modifier->fill($data);
        $modifier->save();
        return response()->json([
            'success' => true,
            'message' => 'Модификация обновлена.'
        ]);
    }

}

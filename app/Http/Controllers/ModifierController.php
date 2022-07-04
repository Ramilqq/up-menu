<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modifier\ModifierUpdateRequest;
use App\Models\Modifier;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    public function show($id)
    {
        $modifier = Modifier::find($id) ?: ['success' => false, 'message' => 'Модификация не найдена.'];
        return response()->json($modifier);
    }

    public function destroy($id)
    {
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

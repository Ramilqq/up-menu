<?php

namespace App\Http\Controllers;

use App\Http\Requests\Table\TableUpdateRequest;
use App\Models\Table;
use App\Policies\TablePolicy;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function show($id)
    {
        if (!TablePolicy::requestShow($id)) return response()->json([]);
        $table = Table::find($id) ?: ['success' => false, 'message' => 'Стол не найден.'];
        return response()->json($table);
    }

    public function destroy($id)
    {
        if (!TablePolicy::requestDelete($id)) return response()->json([]);
        return Table::destroy($id);
    }

    public function update(TableUpdateRequest $request, $id)
    {
        $table = Table::find($id) ?: [];
        if (!$table)
        {
            return response()->json([
                'success' => false,
                'message' => 'Стол не найден.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $table->fill($data);
        $table->save();
        return response()->json([
            'success' => true,
            'message' => 'Стол обновлен.'
        ]);
    }

    public function replace(TableUpdateRequest $request, $id)
    {
        $table = Table::find($id) ?: [];
        if (!$table)
        {
            return response()->json([
                'success' => false,
                'message' => 'Стол не найден.'
            ]);
        }

        $data = $request->validated();

        $table->fill($data);
        $table->save();
        return response()->json([
            'success' => true,
            'message' => 'Стол обновлен.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Dishe\DisheCreateRequest;
use App\Http\Requests\Menu\MenuUpdateRequest;
use App\Http\Requests\Modifier\ModifierCreateRequest;
use App\Models\Dishe;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show($id)
    {
        $menu = Menu::find($id) ?: ['success' => false, 'message' => 'меню не найдено.'];
        return response()->json($menu);
    }

    public function destroy($id)
    {
        return Menu::destroy($id);
    }

    public function update(MenuUpdateRequest $request, $id)
    {
        $menu = Menu::find($id) ?: [];
        if (!$menu)
        {
            return response()->json([
                'success' => false,
                'message' => 'меню не найдено.'
            ]);
        }

        $data = $request->validated();

        $menu->fill($data);
        $menu->save();
        return response()->json([
            'success' => true,
            'message' => 'меню обнавлено.'
        ]);
    }

    public function replace(MenuUpdateRequest $request, $id)
    {
        $menu = Menu::find($id) ?: [];
        if (!$menu)
        {
            return response()->json([
                'success' => false,
                'message' => 'меню не найдено.'
            ]);
        }

        $data = $request->validated();

        $menu->fill($data);
        $menu->save();
        return response()->json([
            'success' => true,
            'message' => 'меню обнавлено.'
        ]);
    }

    public function getDishe($id)
    {
        return Menu::dishe($id);
    }

    public function getCategory($id)
    {
        return Menu::dishe($id);
    }

    public function getModifier($id)
    {
        return Menu::dishe($id);
    }

    public function createDishe(DisheCreateRequest $request, $id)
    {
        $data = $request->validated();
        $dishe = Dishe::create($data) ?: ['success' => false];
        return response()->json($dishe);
    }

    public function createCategory(CategoryCreateRequest $request, $id)
    {
        $data = $request->validated();
        $category = Dishe::create($data) ?: ['success' => false];
        return response()->json($category);
    }

    public function createModifier(ModifierCreateRequest $request, $id)
    {
        $data = $request->validated();
        $modifier = Dishe::create($data) ?: ['success' => false];
        return response()->json($modifier);
    }
    


}

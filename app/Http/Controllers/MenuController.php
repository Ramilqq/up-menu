<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Dishe\DisheCreateRequest;
use App\Http\Requests\Menu\MenuUpdateRequest;
use App\Http\Requests\Modifier\ModifierCreateRequest;
use App\Models\Category;
use App\Models\Dishe;
use App\Models\Menu;
use App\Models\Modifier;
use App\Policies\CategoryPolicy;
use App\Policies\DishePolicy;
use App\Policies\MenuPolicy;
use App\Policies\ModifierPolicy;

class MenuController extends BaseController
{

    public function show($id)
    {
        if (!MenuPolicy::requestShow($id)) return response()->json([]);
        $menu = Menu::find($id) ?: ['success' => false, 'message' => 'Меню не найдено.'];
        return response()->json($menu);
    }

    public function destroy($id)
    {
        if (!MenuPolicy::requestDelete($id)) return response()->json([]);
        $menu = Menu::find($id) ?: [];
        if (!$menu) return [];
        $resault = $menu->delete();
        return response()->json(['success' => (bool) $resault]);
    }

    public function update(MenuUpdateRequest $request, $id)
    {
        $menu = Menu::find($id) ?: [];
        if (!$menu)
        {
            return response()->json([
                'success' => false,
                'message' => 'Меню не найдено.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $menu->fill($data);
        $menu->save();
        return response()->json([
            'success' => true,
            'message' => 'Меню обновлено.'
        ]);
    }

    public function replace(MenuUpdateRequest $request, $id)
    {
        $menu = Menu::find($id) ?: [];
        if (!$menu)
        {
            return response()->json([
                'success' => false,
                'message' => 'Меню не найдено.'
            ]);
        }

        $data = $request->validated();

        $menu->fill($data);
        $menu->save();
        return response()->json([
            'success' => true,
            'message' => 'Меню обновлено.'
        ]);
    }

    public function getDishe($id)
    {
        if (!DishePolicy::requestGet($id)) return response()->json([]);
        return Menu::dishe($id);
    }

    public function getCategory($id)
    {
        if (!CategoryPolicy::requestGet($id)) return response()->json([]);
        return Menu::category($id);
    }

    public function getModifier($id)
    {
        if (!ModifierPolicy::requestGet($id)) return response()->json([]);
        return Menu::modifier($id);
    }

    public function createDishe(DisheCreateRequest $request, $id)
    {
        $data = $request->validated();
        $data = $this->saveImage($data, $request);
        $dishe = Dishe::create($data) ?: ['success' => false];
        return response()->json($dishe);
    }

    public function createCategory(CategoryCreateRequest $request, $id)
    {
        $data = $request->validated();
        $category = Category::create($data) ?: ['success' => false];
        return response()->json($category);
    }

    public function createModifier(ModifierCreateRequest $request, $id)
    {
        $data = $request->validated();
        $modifier = Modifier::create($data) ?: ['success' => false];
        return response()->json($modifier);
    }

}

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

class MenuController extends BaseController
{

    public function show($id)
    {
        $menu = $this->userAndMenu($id) ?: [];
        return response()->json($menu);
    }

    public function destroy($id)
    {
        $menu = $this->userAndMenu($id) ?: null;
        if (!$menu) return [];
        $resault = $menu->delete();
        return response()->json(['success' => (bool) $resault]);
    }

    public function update(MenuUpdateRequest $request, $id)
    {
        $menu = $this->userAndMenu($id) ?: null;
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
        $menu = $this->userAndMenu($id) ?: null;
        if (!$menu) return [];
        return Menu::dishe($menu->id);
    }

    public function getCategory($id)
    {
        $menu = $this->userAndMenu($id) ?: null;
        if (!$menu) return [];
        return Menu::category($id);
    }

    public function getModifier($id)
    {
        $menu = $this->userAndMenu($id) ?: null;
        if (!$menu) return [];
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

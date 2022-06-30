<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::find($id) ?: ['success' => false, 'message' => 'Категория не найдена.'];
        return response()->json($category);
    }

    public function destroy($id)
    {
        return Category::destroy($id);
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id) ?: [];
        if (!$category)
        {
            return response()->json([
                'success' => false,
                'message' => 'Категория не найдена.'
            ]);
        }

        $data = $request->validated();

        $category->fill($data);
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Категория обнавлена.'
        ]);
    }

    public function replace(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id) ?: [];
        if (!$category)
        {
            return response()->json([
                'success' => false,
                'message' => 'Категория не найдена.'
            ]);
        }

        $data = $request->validated();

        $category->fill($data);
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Категория обнавлена.'
        ]);
    }

    public function active($id)
    {
        $category = Category::find($id) ?: [];
        if (!$category)
        {
            return response()->json([
                'success' => false,
                'message' => 'Категория не найдена.'
            ]);
        }
        $category->active = 1;
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Категория включена.'
        ]);
    }

    public function inactive($id)
    {
        $category = Category::find($id) ?: [];
        if (!$category)
        {
            return response()->json([
                'success' => false,
                'message' => 'Категория не найдена.'
            ]);
        }
        $category->active = 0;
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Категория отключена.'
        ]);
    }
}

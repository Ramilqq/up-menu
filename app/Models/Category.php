<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'active',
        'menu_id',
    ];

    static function userAndMenu($menu_id)
    {
        $menu = Menu::find($menu_id) ?: null;
        if (!$menu) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function userCategoryAndMenu($category_id)
    {
        $category = Category::find($category_id) ?: null;
        if (!$category) return false;
        $menu = Menu::find($category->menu_id) ?: null;
        if (!$menu) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

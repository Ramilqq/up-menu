<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'active',
        'order'
    ];

    static function dishe($id)
    {
        return Dishe::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

    static function category($id)
    {
        return Category::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

    static function modifier($id)
    {
        return Modifier::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

    static function getMenuProjectId($id)
    {
        $menu = Menu::find($id) ?: null;
        if (!$menu)
        {
            return null;
        }
        return Project::userAndProject($menu->project_id) ? $menu->project_id : null;
    }

    static function userAndProject($project_id)
    {
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function userMenuAndProject($menu_id)
    {
        $menu = Menu::find($menu_id) ?: null;
        if (!$menu) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

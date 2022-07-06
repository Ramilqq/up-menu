<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'active',
        'menu_id',
        'price',
    ];

    static function getModifierMenuId($id)
    {
        $modifier = Modifier::find($id) ?: null;
        if (!$modifier)
        {
            return null;
        }
        $menu = Menu::find($modifier->menu_id) ?: null;
        if (!$menu)
        {
            return null;
        }
        return Project::userAndProject($menu->project_id) ? $menu->id : null;
    }

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

    static function userModifierAndMenu($modifier_id)
    {
        $modifier = Modifier::find($modifier_id) ?: null;
        if (!$modifier) return false;
        $menu = Menu::find($modifier->menu_id) ?: null;
        if (!$menu) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

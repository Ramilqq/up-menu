<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dishe extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'category_id',
        'name',
        'description',
        'price',
        'photo',
        'order',
        'active',
        'kbju',
        'weight',
        'calories',
    ];

    public function getDisheDestr($id)
    {
        $dishe = '';
        return Dishe::destroy($id);
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

    static function userDisheAndMenu($dishe_id)
    {
        $dishe = Dishe::find($dishe_id) ?: null;
        if (!$dishe) return false;
        $menu = Menu::find($dishe->menu_id) ?: null;
        if (!$menu) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

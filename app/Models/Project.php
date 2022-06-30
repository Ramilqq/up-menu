<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'alias',
        'logo',
        'active',
    ];





    static function projets($id)
    {
        return Project::query()->where('user_id', $id)->get()->toArray() ?: [];
    }

    static function getMenu($id)
    {
        return Menu::query()->where('project_id', $id)->get()->toArray() ?: [];
    }

    /*static function getUser($id, $filter)
    {
        $users = User::query()->where('id', $id)->get()->toArray() ?: [];
        return Menu::query()->where('project_id', $id)->get()->toArray() ?: [];
    }*/

    static function getOrder($id)
    {
        return Order::query()->where('project_id', $id)->get()->toArray() ?: [];
    }
    
    static function getTables($id, $filter)
    {
        $tables = Table::query()->where('project_id', $id);
        if (isset($filter['active']))
        {
            $tables->where('active', $filter['active']);
        }
        return $tables->get()->toArray() ?: [];
    }

    static function getInvite($id)
    {
        return Invite::query()->where('project_id', $id)->get()->toArray() ?: [];
    }
}

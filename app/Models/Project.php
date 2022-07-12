<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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



    static function userAndRole($id)
    {
        return request()->user()->role === User::OWNER;
    }

    static function userAndProject($id)
    {
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $id)->where('user_id', request()->user()->id)->first() ? true : false; 
    }

    static function projet($id)
    {
        $project = Project::query()
            ->where('id', $id)
            ->where('user_id', request()->user()->id)
            ->get()->first();
        return $project;
    }

    static function projets($id)
    {
        $limit = request()->limit ?: 10;
        return Project::query()->where('user_id', $id)->simplePaginate($limit) ?: [];
    }

    static function projetDestroy($id)
    {
        return Project::query()
        ->where('id', $id)
        ->where('user_id', request()->user()->id)
        ->delete();
    }

    static function getMenu($id)
    {
        $limit = request()->limit ?: 10;
        return Menu::query()->where('project_id', $id)->simplePaginate($limit) ?: [];
    }

    static function getUsers($id, $filter)
    {
        $limit = request()->limit ?: 10;
        $users = ProjectUser::query()
        ->where('project_users.project_id', $id)
        ->whereNot('users.role', User::OWNER)
        ->rightJoin('users', 'project_users.user_id', '=', 'users.id')
        ->select('project_users.project_id', 'users.first_name', 'users.last_name', 'users.email', 'users.role');
        
        if ($filter){
            $filter === User::ADMIN ? $role = User::ADMIN : $role = User::USER;
            $users->where('users.role', $role);
        }

        $users = $users->simplePaginate($limit) ?: [];
        return response()->json($users);
    }

    static function getOrder($id)
    {
        $limit = request()->limit ?: 10;
        return Order::query()->where('project_id', $id)->simplePaginate($limit) ?: [];
    }
    
    static function getTables($id, $filter)
    {
        $limit = request()->limit ?: 10;
        $tables = Table::query()->where('tables.project_id', $id)->rightJoin('orders', 'tables.id', '=', 'orders.table_id');
        if (isset($filter['active']))
        {
            $tables->where('tables.active', (bool) $filter['active']);
        }
        if (isset($filter['status']))
        {
            $tables->where('orders.status', $filter['status']);
        }
        $tables->select('tables.id','tables.project_id','tables.name','tables.active','orders.status','tables.created_at','tables.updated_at');
        return $tables->simplePaginate($limit) ?: [];
    }

    static function getInvite($id)
    {
        $limit = request()->limit ?: 10;
        return Invite::query()->where('project_id', $id)->simplePaginate($limit) ?: [];
    }

    static function deleteImage($id)
    {
        if (!$project = Project::query()->where('id', $id)->first()) return;
        if (!$img = $project->logo) return;
        $path_image = 'public/images/project/' . $img;
        Storage::delete($path_image);
    }
}

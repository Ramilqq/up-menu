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
        return Project::query()->where('user_id', $id)->get()->toArray() ?: [];
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
        return Menu::query()->where('project_id', $id)->get()->toArray() ?: [];
    }

    static function getUsers($id, $filter)
    {
        $users = ProjectUser::query()
        ->where('project_users.project_id', $id)
        ->whereNot('users.role', User::OWNER)
        ->rightJoin('users', 'project_users.user_id', '=', 'users.id')
        ->select('project_users.project_id', 'users.first_name', 'users.last_name', 'users.email', 'users.role');
        
        if ($filter){
            $filter === User::ADMIN ? $role = User::ADMIN : $role = User::USER;
            $users->where('users.role', $role);
        }

        $users = $users->get()->toArray() ?: [];
        return response()->json($users);
    }

    static function getOrder($id)
    {
        return Order::query()->where('project_id', $id)->get()->toArray() ?: [];
    }
    
    static function getTables($id, $filter)
    {
        $tables = Table::query()->where('project_id', $id);
        if ($filter !== null)
        {
            $tables->where('active', (bool) $filter);
        }
        return $tables->get()->toArray() ?: [];
    }

    static function getInvite($id)
    {
        return Invite::query()->where('project_id', $id)->get()->toArray() ?: [];
    }

    static function deleteImage($id)
    {
        if (!$project = Project::query()->where('id', $id)->first()) return;
        if (!$img = $project->logo) return;
        $path_image = 'public/images/project/' . $img;
        Storage::delete($path_image);
    }
}

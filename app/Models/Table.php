<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'project_id',
        'name',
        'order',
        'active',
    ];

    static function getTableProjectId($id)
    {
        $table = Table::find($id) ?: null;
        if (!$table)
        {
            return null;
        }
        return Project::userAndProject($table->project_id) ? $table->project_id : null;
    }

    static function userAndProject($project_id)
    {
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function userTableAndProject($table_id)
    {
        $table = Table::find($table_id) ?: null;
        if (!$table) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $table->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $table->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

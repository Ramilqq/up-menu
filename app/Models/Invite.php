<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invite extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'uuid',
        'project_id',
        'inviter_id',
        'invitee_id',
        'type',
        'email',
    ];

    static function userAndProject($project_id)
    {
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function userInviteAndProject($invite_id)
    {
        $invite = Invite::find($invite_id) ?: null;
        if (!$invite) return false;
        if (request()->user()->role === User::OWNER)
        {
            return Project::query()->where('id', $invite->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
        }
        return ProjectUser::query()->where('project_id', $invite->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }
}

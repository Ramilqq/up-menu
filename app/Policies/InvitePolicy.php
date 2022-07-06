<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    static function requestShow($invite_id) {
        return Invite::userInviteAndProject($invite_id);
    }
    
    static function requestGet($project_id) {
        return Invite::userAndProject($project_id);
    }

    static function requestCreate($project_id) {
        return Invite::userAndProject($project_id);
    }

    static function requestUpdate($invite_id) {
        return Invite::userInviteAndProject($invite_id);
    }

    static function requestDelete($invite_id) {
        return Invite::userInviteAndProject($invite_id);
    }

}

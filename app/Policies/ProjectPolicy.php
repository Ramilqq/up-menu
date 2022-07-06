<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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

    static function requestShow($project_id) {
        return Project::userAndProject($project_id);
    }

    static function requestCreate($user_id) {
        return Project::userAndRole($user_id);
    }

    static function requestUpdate($project_id) {
        return Project::userAndProject($project_id);
    }

    static function requestDelete($project_id) {
        return Project::userAndProject($project_id);
    }
}

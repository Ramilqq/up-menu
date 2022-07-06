<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    static function requestShow($user_id) {
        return User::userAndUserProject($user_id);
    }

    static function requestGet($project_id) {
        return User::userAndProject($project_id);
    }

    static function requestUpdate($user_id) {
        return User::userAndUserProject($user_id);
    }

    static function requestDelete($user_id) {
        return User::userAndUserProject($user_id);
    }
}

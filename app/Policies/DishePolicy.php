<?php

namespace App\Policies;

use App\Models\Dishe;
use App\Models\Menu;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DishePolicy
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

    static function requestCreate($menu_id) {
        $menu = Menu::find($menu_id) ?: null;
        if (!$menu) return false;
        return ProjectUser::query()->where('project_id', $menu->project_id)->where('user_id', request()->user()->id)->first() ? true : false;
    }

    static function requestUpdate(User $user)
    {
        return true;
    }

}

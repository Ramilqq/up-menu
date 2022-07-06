<?php

namespace App\Policies;

use App\Models\Dishe;
use App\Models\Menu;
use App\Models\Project;
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

    static function requestShow($dishe_id) {
        return Dishe::userDisheAndMenu($dishe_id);
    }
    
    static function requestGet($menu_id) {
        return Dishe::userAndMenu($menu_id);
    }

    static function requestCreate($menu_id) {
        return Dishe::userAndMenu($menu_id);
    }

    static function requestUpdate($dishe_id) {
        return Dishe::userDisheAndMenu($dishe_id);
    }

    static function requestDelete($dishe_id) {
        return Dishe::userDisheAndMenu($dishe_id);
    }

}

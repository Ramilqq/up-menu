<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\Modifier;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModifierPolicy
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

    static function requestShow($modifier_id) {
        return Modifier::userModifierAndMenu($modifier_id);
    }
    
    static function requestGet($menu_id) {
        return Modifier::userAndMenu($menu_id);
    }

    static function requestCreate($menu_id) {
        return Modifier::userAndMenu($menu_id);
    }

    static function requestUpdate($modifier_id) {
        return Modifier::userModifierAndMenu($modifier_id);
    }

    static function requestDelete($modifier_id) {
        return Modifier::userModifierAndMenu($modifier_id);
    }
}

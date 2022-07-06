<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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

    static function requestShow($category_id) {
        return Category::userCategoryAndMenu($category_id);
    }
    
    static function requestGet($menu_id) {
        return Category::userAndMenu($menu_id);
    }

    static function requestCreate($menu_id) {
        return Category::userAndMenu($menu_id);
    }

    static function requestUpdate($category_id) {
        return Category::userCategoryAndMenu($category_id);
    }

    static function requestDelete($category_id) {
        return Category::userCategoryAndMenu($category_id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function userAndProject($id)
    {
        return Project::userAndProject($id);
    }

    public function userAndMenu($id)
    {
        $menu = Menu::find($id) ?: null;
        if (!$menu) return false;
        if ($this->userAndProject($menu->project_id)) return $menu; else false;
    }

    
}

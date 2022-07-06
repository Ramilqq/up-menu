<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Table;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TablePolicy
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

    static function requestShow($table_id) {
        return Table::userTableAndProject($table_id);
    }
    
    static function requestGet($project_id) {
        return Table::userAndProject($project_id);
    }

    static function requestCreate($project_id) {
        return Table::userAndProject($project_id);
    }

    static function requestUpdate($table_id) {
        return Table::userTableAndProject($table_id);
    }

    static function requestDelete($table_id) {
        return Table::userTableAndProject($table_id);
    }
}

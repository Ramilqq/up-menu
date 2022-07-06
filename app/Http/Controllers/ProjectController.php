<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\InviteCreateRequest;
use App\Http\Requests\Menu\MenuCreateRequest;
use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Requests\Table\TableCreateRequest;
use App\Models\Invite;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Project;
use App\Models\Table;
use App\Policies\InvitePolicy;
use App\Policies\MenuPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TablePolicy;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    public function show($id)
    {
        $project = Project::projet($id) ?: [];
        return response()->json($project);
    }

    public function destroy($id)
    {
        if (!ProjectPolicy::requestDelete($id)) return response()->json(['success' => false]);
        $project = Project::find($id) ?: ['success' => false, 'message' => 'Проект не найден.'];
        $this->deleteImageDishe($project->logo);
        $resault =  $project->delete();
        return response()->json(['success' => (bool) $resault]);
    }

    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::find($id) ?: [];
        if (!$project or $project->user_id !== request()->user()->id)
        {
            return response()->json([
                'success' => false,
                'message' => 'Заведение не найдено.'
            ]);
        }

        $data = $request->validated();
        if (!$data) return response()->json(['success' => true,'message' => 'Нет данных для обновления.']);
        $data = $this->saveImage($data, $request);
        $this->deleteImageProject($project->logo);

        $project->fill($data);
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Заведение обновлено.'
        ]);
    }

    public function replace(ProjectUpdateRequest $request, $id)
    {
        $project = Project::find($id) ?: [];
        if (!$project or $project->user_id !== request()->user()->id)
        {
            return response()->json([
                'success' => false,
                'message' => 'Заведение не найдено.'
            ]);
        }

        $data = $request->validated();
        
        $data = $this->saveImage($data, $request);
        $this->deleteImageProject($project->logo);

        $project->fill($data);
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Заведение обновлено.'
        ]);
    }

    public function create(ProjectCreateRequest $request)
    {
        $data = $request->validated();
        $data = $this->saveImage($data, $request);
        $project = Project::create($data) ?: ['success' => false];
        return response()->json($project);
    }

    public function get()
    {
        return  Project::projets(request()->user()->id);
    }

    public function getMenu($id)
    {
        if (!MenuPolicy::requestGet($id)) return response()->json([]);
        return  Project::getMenu($id);
    }

    public function createMenu(MenuCreateRequest $request, $id)
    {
        $data = $request->validated();
        $menu = Menu::create($data) ?: [];
        return response()->json($menu);
    }

    public function getUser(Request $request, $id)
    {
        if (!UserPolicy::requestGet($id)) return response()->json([]);
        $filter = $request->role ?: null;
        return Project::getUsers($id, $filter);
    }

    public function getOrder($id)
    {
        if (!OrderPolicy::requestGet($id)) return response()->json([]);
        return  Project::getOrder($id);
    }

    public function createOrder(OrderCreateRequest $request, $id)
    {
        $data = $request->validated();
        $order = Order::create($data) ?: [];
        return response()->json($order);
    }

    public function getTable(Request $request, $id)
    {
        if (!TablePolicy::requestGet($id)) return response()->json([]);
        isset($request->active) ? $filter = $request->active : $filter = null;
        return  Project::getTables($id, $filter);
    }

    public function createTable(TableCreateRequest $request, $id)
    {
        $data = $request->validated();
        $table = Table::create($data) ?: [];
        return response()->json($table);
    }

    public function getInvite($id)
    {
        if (!InvitePolicy::requestGet($id)) return response()->json([]);
        return  Project::getInvite($id);
    }

    public function createInvite(InviteCreateRequest $request, $id)
    {
        $data = $request->validated();
        $invite = Invite::create($data) ?: [];
        return response()->json($invite);
    }

}

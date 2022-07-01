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
use App\Models\ProjectUser;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::projet($id) ?: [];
        return response()->json($project);
    }

    public function destroy($id)
    {
        Project::deleteImage($id);
        $resault = Project::projetDestroy($id);
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
        
        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs('public/images/project/'.$id, 'logo.jpg');
            $data['logo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['logo']);
        }

        $project->fill($data);
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Заведение обнавлено.'
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
        
        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs('public/images/project/'.$id, 'logo.jpg');
            $data['logo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['logo']);
        }

        $project->fill($data);
        $project->save();
        return response()->json([
            'success' => true,
            'message' => 'Заведение обнавлено.'
        ]);
    }

    public function create(ProjectCreateRequest $request)
    {
        $data = $request->validated();
        $project = Project::create($data) ?: ['success' => false];
        return response()->json($project);
    }

    public function get()
    {
        return  Project::projets(request()->user()->id);
    }

    public function getMenu($id)
    {
        $inProject = Project::userAndProject($id);
        if (!$inProject) return [];
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
        $inProject = Project::userAndProject($id);
        if (!$inProject) return [];
        $filter = $request->role ?: null;
        return Project::getUsers($id, $filter);
    }

    public function getOrder($id)
    {
        $inProject = Project::userAndProject($id);
        if (!$inProject) return [];
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
        $inProject = Project::userAndProject($id);
        if (!$inProject) return [];
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
        $inProject = Project::userAndProject($id);
        if (!$inProject) return [];
        return  Project::getInvite($id);
    }

    public function createInvite(InviteCreateRequest $request, $id)
    {
        $data = $request->validated();
        $invite = Invite::create($data) ?: [];
        return response()->json($invite);
    }

}

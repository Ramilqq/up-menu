<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\InviteCreateRequest;
use App\Http\Requests\Menu\MenuCreateRequest;
use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Requests\Table\TableCreateRequest;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Project;
use App\Models\Table;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::find($id) ?: ['success' => false, 'message' => 'Заведение не найдено.'];
        return response()->json($project);
    }

    public function destroy($id)
    {
        return Project::destroy($id);
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

        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs('public/images/project/'.$project->id, 'logo.jpg');
            $data['logo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['logo']);
        }

        return response()->json($project);
    }

    public function get()
    {
        return  Project::projets(request()->user()->id);
    }

    public function getMenu()
    {
        return  Project::getMenu(request()->user()->id);
    }

    public function createMenu(MenuCreateRequest $request, $id)
    {
        $data = $request->validated();
        $menu = Menu::create($data) ?: [];
        return response()->json($menu);
    }

    /*public function getUser(Request $request, $id)
    {
        $filter['role'] = $request->role;

        $data = $request->validated();
        $menu = Menu::create($data) ?: [];
        return response()->json($menu);
    }*/

    public function getOrder($id)
    {
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
        $filter['active'] = $request->active;
        return  Project::getTables($id, $filter);
    }

    public function createTable(TableCreateRequest $request, $id)
    {
        $data = $request->validated();
        if($data['project_id'] != $id)
        {
            return response()->json([], 404);
        }
        $table = Table::create($data) ?: [];
        return response()->json($table);
    }

    public function getInvite($id)
    {
        return  Project::getOrder($id);
    }

    public function createInvite(InviteCreateRequest $request, $id)
    {
        $data = $request->validated();
        if($data['project_id'] != $id)
        {
            return response()->json([], 404);
        }
        $invite = Table::create($data) ?: [];
        return response()->json($invite);
    }

}

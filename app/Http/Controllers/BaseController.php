<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function saveImage(array $data, Object $request)
    {
        $path_name = time() . Str::random(5) . '.JPEG';

        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs('public/images/project', $path_name);
            $data['logo'] = str_replace('public', 'storage', $images);
        }elseif ($request->has('photo')) {
            $images = $request->file('photo')->storeAs('public/images/dishe', $path_name);
            $data['photo'] = str_replace('public', 'storage', $images);
        }else{
            unset($data['logo']);
            unset($data['photo']);
        }

        return $data;
    }

    public function deleteImage(string $path)
    {
        if (!$path) return;
        $path_image = str_replace('storage', 'public', $path);
        Storage::delete($path_image);
    }
}

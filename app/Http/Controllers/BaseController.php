<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    public $project_path =  'public/images/project/';
    public $dishe_path =  'public/images/dishe/';
    public $user_path =  'public/images/avatar/';

    public function saveImage(array $data, Object $request)
    {
        $img_name = time() . Str::random(5) . '.jpg';
        
        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs($this->project_path, $img_name);
            $data['logo'] = $img_name;
            $path_save = Storage::path ($this->project_path.$img_name);
        }elseif ($request->has('photo')) {
            $images = $request->file('photo')->storeAs($this->dishe_path, $img_name);
            $data['photo'] = $img_name;
            $path_save = Storage::path ($this->dishe_path.$img_name);
        }elseif ($request->has('avatar')) {
            $images = $request->file('avatar')->storeAs($this->user_path, $img_name);
            $data['avatar'] = $img_name;
            $path_save = Storage::path ($this->user_path.$img_name);
        }else{
            unset($data['logo']);
            unset($data['photo']);
            unset($data['avatar']);
        }

        if (!isset($path_save)) return $data;

        if ( $exif = @exif_read_data($path_save))
        {
            $image = imagecreatefromstring(file_get_contents($path_save));
            if(!empty($exif['Orientation']))
            {
                switch($exif['Orientation']) {
                    case 8:
                        $rotate = imagerotate($image,90,0);
                        break;
                    case 3:
                        $rotate = imagerotate($image,180,0);
                        break;
                    case 6:
                        $rotate = imagerotate($image,-90,0);
                        break;
                }
                if(isset($rotate))
                {
                    imagejpeg($rotate, $path_save);
                }
            }
        }
        return $data;
    }

    public function deleteImageProject($img)
    {
        if (!$img) return;
        $path_image = $this->project_path . $img;
        Storage::delete($path_image);
    }

    public function deleteImageDishe($img)
    {
        if (!$img) return;
        $path_image = $this->dishe_path . $img;
        Storage::delete($path_image);
    }

    public function deleteImageUser($img)
    {
        if (!$img) return;
        $path_image = $this->user_path . $img;
        Storage::delete($path_image);
    }

    public function responceBase($success = true, $msg='', $data=[], $code=200)
    {
        return response()->json([
            'success'   => $success,
            'message'   => $msg,
            'data'      => $data
        ], $code);
    }
}

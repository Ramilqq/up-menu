<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    public function saveImage(array $data, Object $request)
    {
        $img_name = time() . Str::random(5) . '.jpg';
        
        if ($request->has('logo')) {
            $images = $request->file('logo')->storeAs('public/images/project', $img_name);
            $data['logo'] = $img_name;
            $path_save = Storage::path ('public/images/project/'.$img_name);
        }elseif ($request->has('photo')) {
            $images = $request->file('photo')->storeAs('public/images/dishe', $img_name);
            $data['photo'] = $img_name;
            $path_save = Storage::path ('public/images/dishe/'.$img_name);
        }elseif ($request->has('avatar')) {
            $images = $request->file('avatar')->storeAs('public/images/avatar', $img_name);
            $data['avatar'] = $img_name;
            $path_save = Storage::path ('public/images/avatar/'.$img_name);
        }else{
            unset($data['logo']);
            unset($data['photo']);
            unset($data['avatar']);
        }

        if ($exif = @exif_read_data($request->file('logo')))
        {
            $image = imagecreatefromstring(file_get_contents($request->file('logo')));
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
        //$path_image = str_replace('storage', 'public', $path);
        $path_image = 'public/images/project/' . $img;
        Storage::delete($path_image);
    }

    public function deleteImageDishe($img)
    {
        if (!$img) return;
        //$path_image = str_replace('storage', 'public', $path);
        $path_image = 'public/images/dishe/' . $img;
        Storage::delete($path_image);
    }

    public function deleteImageUser($img)
    {
        if (!$img) return;
        //$path_image = str_replace('storage', 'public', $path);
        $path_image = 'public/images/avatar/' . $img;
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'active',
        'order'
    ];

    static function dishe($id)
    {
        return Dishe::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

    static function category($id)
    {
        return Category::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

    static function modifier($id)
    {
        return Modifier::query()->where('menu_id', $id)->get()->toArray() ?: [];
    }

}

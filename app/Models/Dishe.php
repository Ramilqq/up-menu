<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dishe extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'category_id',
        'name',
        'description',
        'price',
        'photo',
        'order',
        'active',
        'kbju',
        'weight',
        'calories',
    ];

    public function getDishe($id)
    {
        $dishe = '';
        return Dishe::destroy($id);
    }
}

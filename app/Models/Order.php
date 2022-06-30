<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const NEW = 'новый';
    const WORK = 'в работе';
    const CLOSE = 'закрыт';
    const PAID = 'оплачен';

    protected $fillable = [
        'project_id',
        'table_id',
        'user_id',
        'name',
        'status',
        'sum',
    ];

}

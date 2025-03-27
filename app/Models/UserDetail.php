<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'avatar ',
        'user_id',
        'address',
        'phone',
        'birthdate',
        'gender',
        'id_card',
    ];
}

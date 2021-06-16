<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersInfo extends Model
{
    use HasFactory;

    protected $table = 'users_info';

    public $timestamps = false;

    protected $fillable = [
        'id',

        'profile_image',
        'profile_background',
    ];

    protected $hidden = ['id'];
}

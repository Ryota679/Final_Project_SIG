<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'TTL',
        'jenis_kelamin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

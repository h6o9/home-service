<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $guard_name = 'staff';

    protected $table = 'staff';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
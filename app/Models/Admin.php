<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'role_id'
    ];
        
}

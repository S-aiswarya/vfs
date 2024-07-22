<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = array('name', 'route', 'guard_name');

    protected $dates = ['created_at','updated_at'];

}
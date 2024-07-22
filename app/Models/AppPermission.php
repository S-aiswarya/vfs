<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class AppPermission extends Model
{
    protected $table = 'app_permissions';

    protected $fillable = array('title', 'permission');

    protected $dates = ['created_at'];

}
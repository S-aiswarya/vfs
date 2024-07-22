<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    
    protected $table = 'roles';

    protected $fillable = ['name', 'guard_name'];

    protected $dates = ['created_at','updated_at'];

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function admins():MorphToMany
    {
        return $this->morphedByMany(Admin::class, 'model', 'model_has_roles', 'role_id');
    }

    public function app_permissions(): BelongsToMany
    {
        return $this->belongsToMany(AppPermission::class, 'role_has_app_permissions', 'role_id', 'permission_id');
    }

}
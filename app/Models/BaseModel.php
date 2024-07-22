<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Schema, Auth;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class BaseModel extends Model {
    use Loggable;

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if(Schema::hasColumn($model->getTableName(), 'created_by')) {
                if($user = Auth::user())
                    $model->created_by = $user->id;
            }
        });
        
        static::saving(function ($model) {
            if(Schema::hasColumn($model->getTableName(), 'updated_by')) {
                if($user = Auth::user()){
                    if(self::getTableName() == "leads" && Auth::getDefaultDriver() == "admin")
                        $model->updated_by_admin_id = $user->id;
                    else
                        $model->updated_by = $user->id;
                }
            }
        });
    }
    
    public static function getTableName() {
        return with(new static)->getTable();
    }

    public function created_user() {
        if(Schema::hasColumn($this->getTableName(), 'created_by')) {
            return $this->belongsTo(User::class, 'created_by');
        }
    }

    public function updated_user() {
        if(Schema::hasColumn($this->getTableName(), 'updated_by')) {
            return $this->belongsTo(User::class, 'updated_by');
        }
    }

    public function created_admin() {
        if(Schema::hasColumn($this->getTableName(), 'created_by_admin_id')) {
            return $this->belongsTo('App\Models\Admin', 'created_by_admin_id');
        }
        return null;
    }

    public function updated_admin() {
        if(Schema::hasColumn($this->getTableName(), 'updated_by_admin_id')) {
            return $this->belongsTo('App\Models\Admin', 'updated_by_admin_id');
        }
        return null;
    }

}

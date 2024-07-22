<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel as model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Guard extends Model
{
    use HasFactory;

    
    use SoftDeletes;
    

    protected $table = 'guard_sign_in';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }   

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'center_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel as model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Center extends Model
{
    use HasFactory;

    
    use SoftDeletes;

    protected $table = 'centers';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id','id');
    }
}

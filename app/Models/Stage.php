<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use SoftDeletes;

    protected $table = 'stages';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function sub_stages():HasMany
    {
        return $this->hasMany(Stage::class, 'parent_id')->orderBy('processing_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'parent_id', 'id');
    }

    public function nextPossibleStages(): BelongsToMany
    {
        return $this->belongsToMany(Stage::class, 'stage_next_stage', 'stage_id', 'next_possible_stage_id')->withPivot('created_by', 'updated_by', 'created_at', 'updated_at');
    }

}
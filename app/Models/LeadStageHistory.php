<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStageHistory extends Model
{
    use SoftDeletes;

    protected $table = 'lead_stage_history';

    public $timestamps = false;

    protected $guarded = ['id', 'created_by', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

}
<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'document_templates';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

}
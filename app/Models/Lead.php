<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'leads';

    protected $guarded = ['id', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function assignedToOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'assign_to_office_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_to_user_id');
    }

    public function timeline(): MorphMany
    {
        return $this->morphMany(ApiLog::class, 'relatable')->orderBy('created_at', 'DESC');
    }

    public function leadSource(): BelongsTo{
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(LeadStageHistory::class, 'lead_id')->orderBy('created_at', 'ASC');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'lead_id');
    }

    public function latestApplication(): HasOne
    {
        return $this->hasOne(Application::class, 'lead_id')->latestOfMany();
    }
}
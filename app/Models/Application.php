<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $table = 'applications';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];


    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function citizenshipCountry(): BelongsTo
    {
        return $this->belongsTo(CountryList::class, 'citizenship_country_id');
    }

    public function documents() :HasMany
    {
        return $this->hasMany(Document::class, 'application_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(ApplicationStatus::class, 'application_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function universityDocuments(): HasMany
    {
        return $this->hasMany(ApplicationUniversityDocument::class, 'application_id');
    }

}
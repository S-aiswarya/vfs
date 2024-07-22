<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunicationLog extends Model
{
    use SoftDeletes;

    protected $table = 'communication_logs';

    protected $guarded = ['id', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

    public $timestamps = false;

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'from_university_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'from_agency_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class, 'email_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

}
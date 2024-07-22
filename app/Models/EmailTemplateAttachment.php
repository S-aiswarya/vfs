<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplateAttachment extends Model
{
    use SoftDeletes;

    protected $table = 'email_template_attachments';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function createdByAdmin(): BelongsTo{
        return $this->belongsTo(Admin::class, 'created_by_admin');
    }

    public function updatedByAdmin(): BelongsTo{
        return $this->belongsTo(Admin::class, 'updated_by_admin');
    }

    public function createdByUser(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user');
    }

    public function updatedByUser(): BelongsTo{
        return $this->belongsTo(User::class, 'updated_by_user');
    }

}
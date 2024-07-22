<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadDeferHistory extends Model
{
    use SoftDeletes;

    protected $table = 'lead_defer_history';

    public $timestamps = false;

    protected $guarded = ['id', 'created_by', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

}
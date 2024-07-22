<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationStatus extends Model
{
    use SoftDeletes;

    protected $table = 'application_status_history';

    protected $guarded = ['id', 'created_by', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

    public $timestamps = false;

}
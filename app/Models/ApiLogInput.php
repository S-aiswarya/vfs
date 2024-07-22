<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLogInput extends Model
{
    use SoftDeletes;

    protected $table = 'api_log_inputs';

    protected $guarded = ['id', 'api_log_id', 'created_by', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

    public $timestamps = false;

}
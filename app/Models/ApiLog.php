<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLog extends Model
{
    use SoftDeletes;

    protected $table = 'api_logs';

    protected $guarded = ['id', 'created_by', 'created_at', 'deleted_at'];

    public $timestamps = false;

    protected $dates = ['created_at'];

    public function relatedInputs(): HasOne
    {
        return $this->hasOne(ApiLogInput::class, 'api_log_id');
    }

}
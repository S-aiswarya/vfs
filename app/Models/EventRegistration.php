<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use SoftDeletes;

    protected $table = 'event_registrations';

    protected $guarded = ['id', 'created_at', 'deleted_at'];

    protected $dates = ['created_at'];

    public $timestamps = false;

}
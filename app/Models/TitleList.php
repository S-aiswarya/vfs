<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitleList extends Model
{
    use SoftDeletes;

    protected $table = 'titles';

    protected $dates = ['created_at','updated_at'];

}
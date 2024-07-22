<?php
namespace App\Models;

use App\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryList extends Model
{
    use SoftDeletes;

    protected $table = 'countries';

    protected $dates = ['created_at','updated_at'];

}
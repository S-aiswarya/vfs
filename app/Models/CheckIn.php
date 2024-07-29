<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Location;
use App\Models\City;
use App\Models\Center;
use App\Models\Country;
use App\Models\Gate;
use App\Models\Employ;
use App\Models\Register_type;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'check_ins';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    // public function check_in_type(): BelongsTo
    // {
    //     return $this->belongsTo(check_in_type::class, 'check_in_type_id','id');
    // }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id','id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id','id');
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id','id');
    }
    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'center_id','id');
    }
    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class, 'gate_id','id');
    }
    public function key_types(): BelongsTo
    {
        return $this->belongsTo(KeyType::class,'key_id','id');
    }
    public function employees(): BelongsTo
    {
        return $this->belongsTo(Employ::class,'employ_id','id');
    }
    public function register_types(): BelongsTo
    {
        return $this->belongsTo(Register_type::class,'check_in_type_id','id');
    }
   

}

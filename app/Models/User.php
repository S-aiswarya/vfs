<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard = 'user';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role_id',
        'manager_id',
        'office_country_id',
        'city_id',
        'location_id',
        'center_id',
        'gate_id',
        'address'
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'user_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function counsellors(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function targets(): HasMany{
        return $this->hasMany(UserTarget::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(Office::class, 'office_user', 'user_id', 'office_id');
    }

    public function applicationCoordinatorOffices(): HasMany
    {
        return $this->hasMany(Office::class, 'application_coordinator_id');
    }

    public function officeCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'office_country_id');
    }

    public function userTargetsApplicationSubmitted($intake){
        return $this->targets()->where('intake_id', $intake)->value('application_submitted');
    }

    public function userTargetsUnconditionalOffers($intake){
        return $this->targets()->where('intake_id', $intake)->value('unconditional_offers');
    }

    public function userTargetsDepositPaid($intake){
        return $this->targets()->where('intake_id', $intake)->value('deposit_paid');
    }

    public function userTargetsVisaObtained($intake){
        return $this->targets()->where('intake_id', $intake)->value('visa_obtained');
    }

    

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id','id');
    }

    public function center_location(): BelongsTo
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
        
}

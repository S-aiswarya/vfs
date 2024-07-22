<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard = 'student';

    protected $guarded = ['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    protected $dates = ['created_at','updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function countryOfBirth(): BelongsTo
    {
        return $this->belongsTo(CountryList::class, 'country_of_birth_id');
    }

    public function countryOfResidence(): BelongsTo
    {
        return $this->belongsTo(CountryList::class, 'country_of_residence_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'student_id');
    }

    public function intake(): BelongsTo
    {
        return $this->belongsTo(Intake::class, 'intake_id');
    }
        
}

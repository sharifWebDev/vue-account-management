<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'mobile',
        'email',
        'password',
        'date_of_birth',
        'joining_date',
        'image',
        'facebook',
        'twitter',
        'instagram',
        'google_plus',
        'linkedin',
        'company_id',
        'user_type',
        'created',
        'modified',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'company_id' => 'array',
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_login_ip' => 'string',
        'current_team_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'created' => 'datetime',
        'modified' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'status' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
